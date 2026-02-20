<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contestant;
use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with('creator')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contestants = Contestant::orderBy('name')->get();
        $questions = Question::orderBy('id')->get();
        return view('admin.events.create', compact('contestants', 'questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'started_at' => 'required|date',
            'status' => 'required|in:draft,running,paused,finished',
            'contestant_ids' => 'nullable|array',
            'contestant_ids.*' => 'integer|exists:contestants,id',
            'question_ids' => 'nullable|array',
            'question_ids.*' => 'integer|exists:questions,id',
            'question_seq' => 'nullable|array',
            'question_seq.*' => 'nullable|integer|min:1',
        ]);

        $validated['started_at'] = Carbon::parse($validated['started_at']);
        $event = new Event($validated);
        $event->created_by = Auth::id();
        $event->save();
        $event->contestants()->sync($request->input('contestant_ids', []));
        $this->syncEventQuestions(
            $event,
            $request->input('question_ids', []),
            $request->input('question_seq', [])
        );

        return redirect()->route('events.questions.index')
            ->with('success', 'Acara berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['questions' => function ($query) {
            $query->orderByPivot('seq');
        }, 'contestants']);

        $contestantPoints = $event->answers()
            ->selectRaw('contestant_id, COALESCE(SUM(points_awarded), 0) as total_points')
            ->groupBy('contestant_id')
            ->pluck('total_points', 'contestant_id');

        $currentQuestion = null;
        $currentQuestionAnswers = collect();

        if ($event->current_question_seq) {
            $currentQuestion = $event->questions()
                ->wherePivot('seq', $event->current_question_seq)
                ->with('options')
                ->first();

            if ($currentQuestion) {
                $currentQuestionAnswers = $event->answers()
                    ->with('contestant')
                    ->where('question_id', $currentQuestion->id)
                    ->orderByDesc('points_awarded')
                    ->orderBy('contestant_id')
                    ->get();
            }
        }

        return view('admin.events.show', compact('event', 'currentQuestion', 'contestantPoints', 'currentQuestionAnswers'));
    }

    public function questionsIndex()
    {
        $events = Event::with('creator')->withCount('questions')->latest()->paginate(10);
        return view('admin.events.questions-index', compact('events'));
    }

    public function editQuestions(Event $event)
    {
        $questions = Question::orderBy('id')->get();
        $event->load('questions');
        $selectedQuestionIds = $event->questions->pluck('id')->toArray();
        $questionSeqs = $event->questions->pluck('pivot.seq', 'id')->toArray();

        return view('admin.events.questions', compact('event', 'questions', 'selectedQuestionIds', 'questionSeqs'));
    }

    public function downloadQuestionsTemplate(Event $event)
    {
        $headers = [
            'Pertanyaan',
            'Tingkat Kesulitan',
            'Durasi (detik)',
            'Penjelasan',
            'Pilihan A',
            'Pilihan B',
            'Pilihan C',
            'Pilihan D',
            'Jawaban Benar',
        ];

        $example = [
            'Contoh pertanyaan?',
            1,
            30,
            'Penjelasan opsional',
            'Opsi A',
            'Opsi B',
            'Opsi C',
            'Opsi D',
            'A',
        ];

        $tempDir = storage_path('app/tmp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $fileName = "template-soal-acara-{$event->id}.xlsx";
        $filePath = $tempDir . DIRECTORY_SEPARATOR . $fileName;

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);
        $writer->addRow(WriterEntityFactory::createRowFromArray($headers));
        $writer->addRow(WriterEntityFactory::createRowFromArray($example));
        $writer->close();

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    public function importQuestions(Request $request, Event $event)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,txt|max:5120',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        $expectedHeaders = [
            'Pertanyaan',
            'Tingkat Kesulitan',
            'Durasi (detik)',
            'Penjelasan',
            'Pilihan A',
            'Pilihan B',
            'Pilihan C',
            'Pilihan D',
            'Jawaban Benar',
        ];

        if ($extension === 'csv' || $extension === 'txt') {
            return $this->importQuestionsFromCsv($file->getRealPath(), $event, $expectedHeaders);
        }

        $reader = new Reader();
        $reader->open($file->getRealPath());

        $headerMap = null;
        $rows = [];
        $rowNumber = 0;

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowNumber++;
                $values = [];
                foreach ($row->getCells() as $cell) {
                    $values[] = is_string($cell->getValue()) ? trim($cell->getValue()) : $cell->getValue();
                }

                $isEmptyRow = count(array_filter($values, function ($value) {
                    return $value !== null && $value !== '';
                })) === 0;
                if ($isEmptyRow) {
                    continue;
                }

                if ($headerMap === null) {
                    $headerMap = [];
                    foreach ($values as $index => $header) {
                        $headerMap[(string) $header] = $index;
                    }

                    $missing = array_filter($expectedHeaders, function ($header) use ($headerMap) {
                        return !array_key_exists($header, $headerMap);
                    });

                    if (!empty($missing)) {
                        $reader->close();
                        return back()->withErrors([
                            'import' => 'Header tidak sesuai. Kolom wajib: ' . implode(', ', $expectedHeaders),
                        ]);
                    }
                    continue;
                }

                $data = [];
                foreach ($expectedHeaders as $header) {
                    $index = $headerMap[$header];
                    $data[$header] = $values[$index] ?? null;
                }
                $data['_row'] = $rowNumber;
                $rows[] = $data;
            }
            break;
        }

        $reader->close();

        if (empty($rows)) {
            return back()->withErrors(['import' => 'Tidak ada data yang dapat diimpor.']);
        }

        $errors = [];
        $prepared = [];

        foreach ($rows as $row) {
            $rowLabel = 'Baris ' . $row['_row'];
            $questionText = trim((string) ($row['Pertanyaan'] ?? ''));
            $difficulty = (int) ($row['Tingkat Kesulitan'] ?? 0);
            $duration = (int) ($row['Durasi (detik)'] ?? 0);
            $explanation = trim((string) ($row['Penjelasan'] ?? ''));
            $optionA = trim((string) ($row['Pilihan A'] ?? ''));
            $optionB = trim((string) ($row['Pilihan B'] ?? ''));
            $optionC = trim((string) ($row['Pilihan C'] ?? ''));
            $optionD = trim((string) ($row['Pilihan D'] ?? ''));
            $correctRaw = trim((string) ($row['Jawaban Benar'] ?? ''));

            if ($questionText === '') {
                $errors[] = "{$rowLabel}: Pertanyaan wajib diisi.";
                continue;
            }
            if ($difficulty < 1 || $difficulty > 5) {
                $errors[] = "{$rowLabel}: Tingkat Kesulitan harus 1-5.";
                continue;
            }
            if ($duration < 1 || $duration > 3600) {
                $errors[] = "{$rowLabel}: Durasi (detik) harus 1-3600.";
                continue;
            }
            if ($optionA === '' || $optionB === '') {
                $errors[] = "{$rowLabel}: Pilihan A dan B wajib diisi.";
                continue;
            }
            if ($optionC === '' && $optionD !== '') {
                $errors[] = "{$rowLabel}: Pilihan C wajib diisi jika Pilihan D diisi.";
                continue;
            }

            $correct = strtoupper($correctRaw);
            if (in_array($correct, ['1', '2', '3', '4'], true)) {
                $correct = chr(64 + (int) $correct);
            }

            $options = [
                'A' => $optionA,
                'B' => $optionB,
            ];
            if ($optionC !== '') {
                $options['C'] = $optionC;
            }
            if ($optionD !== '') {
                $options['D'] = $optionD;
            }

            if (!array_key_exists($correct, $options)) {
                $errors[] = "{$rowLabel}: Jawaban Benar harus salah satu dari pilihan yang terisi (A-D).";
                continue;
            }

            $prepared[] = [
                'question_text' => $questionText,
                'difficulty_level' => $difficulty,
                'duration' => $duration,
                'explanation' => $explanation !== '' ? $explanation : null,
                'options' => $options,
                'correct' => $correct,
            ];
        }

        if (!empty($errors)) {
            return back()->withErrors(['import' => implode(' | ', $errors)]);
        }

        DB::transaction(function () use ($event, $prepared) {
            $nextSeq = (int) ($event->questions()->max('seq') ?? 0) + 1;

            foreach ($prepared as $item) {
                $question = Question::create([
                    'question_text' => $item['question_text'],
                    'difficulty_level' => $item['difficulty_level'],
                    'explanation' => $item['explanation'],
                    'duration' => $item['duration'],
                ]);

                foreach ($item['options'] as $label => $text) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'label' => $label,
                        'option_text' => $text,
                        'is_correct' => $label === $item['correct'],
                    ]);
                }

                $event->questions()->attach($question->id, ['seq' => $nextSeq]);
                $nextSeq++;
            }
        });

        return back()->with('success', 'Import soal berhasil. Soal langsung ditambahkan ke acara ini.');
    }

    private function importQuestionsFromCsv(string $path, Event $event, array $expectedHeaders)
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return back()->withErrors(['import' => 'File CSV tidak bisa dibuka.']);
        }

        $headerMap = null;
        $rows = [];
        $rowNumber = 0;

        while (($data = fgetcsv($handle)) !== false) {
            $rowNumber++;
            if ($data === [null] || $data === false) {
                continue;
            }

            $values = array_map(function ($value) {
                return is_string($value) ? trim($value) : $value;
            }, $data);

            $isEmptyRow = count(array_filter($values, function ($value) {
                return $value !== null && $value !== '';
            })) === 0;
            if ($isEmptyRow) {
                continue;
            }

            if ($headerMap === null) {
                $headerMap = [];
                foreach ($values as $index => $header) {
                    $headerMap[(string) $header] = $index;
                }

                $missing = array_filter($expectedHeaders, function ($header) use ($headerMap) {
                    return !array_key_exists($header, $headerMap);
                });

                if (!empty($missing)) {
                    fclose($handle);
                    return back()->withErrors([
                        'import' => 'Header tidak sesuai. Kolom wajib: ' . implode(', ', $expectedHeaders),
                    ]);
                }
                continue;
            }

            $data = [];
            foreach ($expectedHeaders as $header) {
                $index = $headerMap[$header];
                $data[$header] = $values[$index] ?? null;
            }
            $data['_row'] = $rowNumber;
            $rows[] = $data;
        }

        fclose($handle);

        if (empty($rows)) {
            return back()->withErrors(['import' => 'Tidak ada data yang dapat diimpor.']);
        }

        $errors = [];
        $prepared = [];

        foreach ($rows as $row) {
            $rowLabel = 'Baris ' . $row['_row'];
            $questionText = trim((string) ($row['Pertanyaan'] ?? ''));
            $difficulty = (int) ($row['Tingkat Kesulitan'] ?? 0);
            $duration = (int) ($row['Durasi (detik)'] ?? 0);
            $explanation = trim((string) ($row['Penjelasan'] ?? ''));
            $optionA = trim((string) ($row['Pilihan A'] ?? ''));
            $optionB = trim((string) ($row['Pilihan B'] ?? ''));
            $optionC = trim((string) ($row['Pilihan C'] ?? ''));
            $optionD = trim((string) ($row['Pilihan D'] ?? ''));
            $correctRaw = trim((string) ($row['Jawaban Benar'] ?? ''));

            if ($questionText === '') {
                $errors[] = "{$rowLabel}: Pertanyaan wajib diisi.";
                continue;
            }
            if ($difficulty < 1 || $difficulty > 5) {
                $errors[] = "{$rowLabel}: Tingkat Kesulitan harus 1-5.";
                continue;
            }
            if ($duration < 1 || $duration > 3600) {
                $errors[] = "{$rowLabel}: Durasi (detik) harus 1-3600.";
                continue;
            }
            if ($optionA === '' || $optionB === '') {
                $errors[] = "{$rowLabel}: Pilihan A dan B wajib diisi.";
                continue;
            }
            if ($optionC === '' && $optionD !== '') {
                $errors[] = "{$rowLabel}: Pilihan C wajib diisi jika Pilihan D diisi.";
                continue;
            }

            $correct = strtoupper($correctRaw);
            if (in_array($correct, ['1', '2', '3', '4'], true)) {
                $correct = chr(64 + (int) $correct);
            }

            $options = [
                'A' => $optionA,
                'B' => $optionB,
            ];
            if ($optionC !== '') {
                $options['C'] = $optionC;
            }
            if ($optionD !== '') {
                $options['D'] = $optionD;
            }

            if (!array_key_exists($correct, $options)) {
                $errors[] = "{$rowLabel}: Jawaban Benar harus salah satu dari pilihan yang terisi (A-D).";
                continue;
            }

            $prepared[] = [
                'question_text' => $questionText,
                'difficulty_level' => $difficulty,
                'duration' => $duration,
                'explanation' => $explanation !== '' ? $explanation : null,
                'options' => $options,
                'correct' => $correct,
            ];
        }

        if (!empty($errors)) {
            return back()->withErrors(['import' => implode(' | ', $errors)]);
        }

        DB::transaction(function () use ($event, $prepared) {
            $nextSeq = (int) ($event->questions()->max('seq') ?? 0) + 1;

            foreach ($prepared as $item) {
                $question = Question::create([
                    'question_text' => $item['question_text'],
                    'difficulty_level' => $item['difficulty_level'],
                    'explanation' => $item['explanation'],
                    'duration' => $item['duration'],
                ]);

                foreach ($item['options'] as $label => $text) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'label' => $label,
                        'option_text' => $text,
                        'is_correct' => $label === $item['correct'],
                    ]);
                }

                $event->questions()->attach($question->id, ['seq' => $nextSeq]);
                $nextSeq++;
            }
        });

        return back()->with('success', 'Import soal berhasil. Soal langsung ditambahkan ke acara ini.');
    }

    public function updateQuestions(Request $request, Event $event)
    {
        $validated = $request->validate([
            'question_ids' => 'nullable|array',
            'question_ids.*' => 'integer|exists:questions,id',
            'question_seq' => 'nullable|array',
            'question_seq.*' => 'nullable|integer|min:1',
        ]);

        $this->syncEventQuestions(
            $event,
            $request->input('question_ids', []),
            $request->input('question_seq', [])
        );

        return redirect()->route('events.questions.edit', $event->id)
            ->with('success', 'Soal acara berhasil diperbarui.');
    }

    public function startIntro(Event $event)
    {
        $startedAt = $event->started_at ?? now();
        $event->update([
            'status' => 'running',
            'is_intro' => true,
            'quiz_started' => false,
            'current_question_seq' => null,
            'question_state' => null,
            'timer_started_at' => null,
            'timer_stopped_at' => null,
            'started_at' => $startedAt,
        ]);

        return back()->with('success', 'Acara dimulai (Intro).');
    }

    public function startQuiz(Event $event)
    {
        $event->update([
            'is_intro' => false,
            'quiz_started' => true,
            'current_question_seq' => 1,
            'question_state' => 'blurred',
            'timer_started_at' => null,
            'timer_stopped_at' => null,
        ]);

        return back()->with('success', 'Kuis dimulai!');
    }

    public function nextQuestion(Event $event)
    {
        $maxSeq = $event->questions()->max('seq');

        if ($event->current_question_seq && $event->current_question_seq < $maxSeq) {
            $event->update([
                'current_question_seq' => $event->current_question_seq + 1,
                'question_state' => 'blurred',
                'timer_started_at' => null,
                'timer_stopped_at' => null,
            ]);
        }

        return back();
    }

    public function prevQuestion(Event $event)
    {
        if ($event->current_question_seq && $event->current_question_seq > 1) {
            $event->update([
                'current_question_seq' => $event->current_question_seq - 1,
                'question_state' => 'blurred',
                'timer_started_at' => null,
                'timer_stopped_at' => null,
            ]);
        }

        return back();
    }

    public function unblurQuestion(Event $event)
    {
        $event->update([
            'question_state' => 'unblurred',
            'timer_started_at' => now()->timestamp,
            'timer_stopped_at' => null,
        ]);

        return back();
    }

    public function stopTimer(Event $event)
    {
        $event->update([
            'timer_stopped_at' => now()->timestamp,
        ]);

        return back();
    }

    public function revealAnswer(Event $event)
    {
        $event->update([
            'question_state' => 'revealed',
        ]);

        return back();
    }

    public function awardPoints(Request $request, Event $event)
    {
        $request->validate([
            'contestant_ids' => 'required|array',
            'contestant_ids.*' => 'required|integer|exists:contestants,id',
            'points' => 'required|integer',
        ]);

        $currentQuestion = $event->questions()
            ->wherePivot('seq', $event->current_question_seq)
            ->first();

        if (!$currentQuestion) {
            return back()->withErrors(['points' => 'Soal aktif tidak ditemukan.']);
        }

        foreach ($request->contestant_ids as $contestantId) {
            EventAnswer::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'question_id' => $currentQuestion->id,
                    'contestant_id' => $contestantId,
                ],
                [
                    'is_correct' => true,
                    'marked_by' => auth()->id(),
                    'marked_at' => now(),
                    'points_awarded' => $request->points,
                ]
            );
        }

        return back()->with('success', 'Poin berhasil diberikan.');
    }

    public function updateAwardedPoints(Request $request, Event $event, EventAnswer $eventAnswer)
    {
        if ($eventAnswer->event_id !== $event->id) {
            abort(404);
        }

        $validated = $request->validate([
            'points' => 'required|integer',
        ]);

        $eventAnswer->update([
            'points_awarded' => $validated['points'],
            'marked_by' => auth()->id(),
            'marked_at' => now(),
        ]);

        return back()->with('success', 'Poin peserta berhasil diperbarui.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $contestants = Contestant::orderBy('name')->get();
        $selectedContestantIds = $event->contestants()->pluck('contestants.id')->toArray();
        $questions = Question::orderBy('id')->get();
        $event->load('questions');
        $selectedQuestionIds = $event->questions->pluck('id')->toArray();
        $questionSeqs = $event->questions->pluck('pivot.seq', 'id')->toArray();

        return view('admin.events.edit', compact(
            'event',
            'contestants',
            'selectedContestantIds',
            'questions',
            'selectedQuestionIds',
            'questionSeqs'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'started_at' => 'required|date',
            'status' => 'required|in:draft,running,paused,finished',
            'contestant_ids' => 'nullable|array',
            'contestant_ids.*' => 'integer|exists:contestants,id',
            'question_ids' => 'nullable|array',
            'question_ids.*' => 'integer|exists:questions,id',
            'question_seq' => 'nullable|array',
            'question_seq.*' => 'nullable|integer|min:1',
        ]);

        $validated['started_at'] = Carbon::parse($validated['started_at']);
        $event->update($validated);
        $event->contestants()->sync($request->input('contestant_ids', []));
        $this->syncEventQuestions(
            $event,
            $request->input('question_ids', []),
            $request->input('question_seq', [])
        );

        return redirect()->route('events.questions.index')
            ->with('success', 'Acara berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.questions.index')
            ->with('success', 'Acara berhasil dihapus.');
    }

    private function syncEventQuestions(Event $event, array $questionIds, array $questionSeqs): void
    {
        $questionIds = array_values(array_unique(array_filter($questionIds)));

        if (empty($questionIds)) {
            $event->questions()->detach();
            return;
        }

        $items = [];
        foreach ($questionIds as $questionId) {
            $seq = $questionSeqs[$questionId] ?? null;
            $seq = is_numeric($seq) ? (int) $seq : null;
            $items[] = [
                'id' => (int) $questionId,
                'seq' => $seq,
            ];
        }

        usort($items, function ($a, $b) {
            $aSeq = $a['seq'] ?? PHP_INT_MAX;
            $bSeq = $b['seq'] ?? PHP_INT_MAX;
            if ($aSeq === $bSeq) {
                return $a['id'] <=> $b['id'];
            }
            return $aSeq <=> $bSeq;
        });

        $syncData = [];
        foreach ($items as $index => $item) {
            $syncData[$item['id']] = ['seq' => $index + 1];
        }

        $event->questions()->sync($syncData);
    }
}
