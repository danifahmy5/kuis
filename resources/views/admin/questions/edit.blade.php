@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Soal') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('questions.update', $question->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="question_text" class="form-label">{{ __('Pertanyaan') }}</label>
                            <textarea id="question_text" class="form-control @error('question_text') is-invalid @enderror" name="question_text" rows="3" required autofocus>{{ old('question_text', $question->question_text) }}</textarea>
                            @error('question_text')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="difficulty_level" class="form-label">{{ __('Tingkat Kesulitan') }}</label>
                                <select name="difficulty_level" id="difficulty_level" class="form-control @error('difficulty_level') is-invalid @enderror">
                                    <option value="1" {{ old('difficulty_level', $question->difficulty_level) == 1 ? 'selected' : '' }}>1 - Mudah</option>
                                    <option value="2" {{ old('difficulty_level', $question->difficulty_level) == 2 ? 'selected' : '' }}>2 - Sedang</option>
                                    <option value="3" {{ old('difficulty_level', $question->difficulty_level) == 3 ? 'selected' : '' }}>3 - Sulit</option>
                                    <option value="4" {{ old('difficulty_level', $question->difficulty_level) == 4 ? 'selected' : '' }}>4 - Sangat Sulit</option>
                                    <option value="5" {{ old('difficulty_level', $question->difficulty_level) == 5 ? 'selected' : '' }}>5 - Expert</option>
                                </select>
                                @error('difficulty_level')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="explanation" class="form-label">{{ __('Penjelasan (Opsional)') }}</label>
                            <textarea id="explanation" class="form-control @error('explanation') is-invalid @enderror" name="explanation" rows="2">{{ old('explanation', $question->explanation) }}</textarea>
                            @error('explanation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <hr>
                        <h5>Pilihan Jawaban</h5>
                        <p class="text-muted small">Edit teks pilihan jawaban dan pilih salah satu sebagai jawaban yang benar.</p>

                        @foreach($question->options as $index => $option)
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="{{ $index }}" {{ old('correct_option', $option->is_correct ? $index : -1) == $index ? 'checked' : '' }} aria-label="Radio button for following text input">
                                    <span class="ms-2 fw-bold">{{ $option->label }}</span>
                                </div>
                                <input type="text" class="form-control" name="options[]" value="{{ old('options.'.$index, $option->option_text) }}" required>
                            </div>
                        @endforeach

                        {{-- If for some reason options are missing in DB, provide empty inputs --}}
                        @if($question->options->isEmpty())
                            @foreach(range(0, 3) as $index)
                                <div class="input-group mb-3">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="radio" name="correct_option" value="{{ $index }}" {{ $index == 0 ? 'checked' : '' }}>
                                        <span class="ms-2 fw-bold">{{ chr(65 + $index) }}</span>
                                    </div>
                                    <input type="text" class="form-control" name="options[]" placeholder="Teks Jawaban {{ chr(65 + $index) }}" required>
                                </div>
                            @endforeach
                        @endif
                        
                        @error('options')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="row mb-0 mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Perbarui Soal') }}
                                </button>
                                <a href="{{ route('questions.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
