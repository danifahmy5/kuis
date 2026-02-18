<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} - Quiz Display</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0d6efd;
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }
        .container {
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
        }
        .blurred {
            filter: blur(20px);
            pointer-events: none;
            user-select: none;
        }
        .timer {
            font-size: 5rem;
            font-weight: bold;
            margin: 20px 0;
            color: #ffc107;
        }
        .question-text {
            font-size: 2.5rem;
            margin-bottom: 30px;
        }
        .options-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .option-item {
            background: white;
            color: #0d6efd;
            padding: 20px;
            font-size: 1.5rem;
            border-radius: 10px;
            text-align: left;
            transition: all 0.3s;
        }
        .option-item.correct {
            background: #198754;
            color: white;
        }
        .intro-title {
            font-size: 4rem;
            font-weight: bold;
        }
        .intro-desc {
            font-size: 2rem;
        }
    </style>
</head>
<body>
    <div class="container" id="app">
        {{-- Content will be updated by JavaScript --}}
        <h1>Loading...</h1>
    </div>

    <script>
        const eventId = {{ $event->id }};
        let lastState = null;
        let timerInterval = null;

        async function fetchState() {
            try {
                const response = await fetch(`/display/${eventId}/state`);
                const data = await response.json();
                updateUI(data);
            } catch (error) {
                console.error("Error fetching state:", error);
            }
        }

        function updateUI(data) {
            const app = document.getElementById('app');
            const config = data.event.config || {};
            
            // Check if intro
            if (config.is_intro) {
                app.innerHTML = `
                    <div class="intro-title">${data.event.title}</div>
                    <div class="intro-desc mt-4">${data.event.title}</div>
                    <p class="mt-5">Persiapan dimulai...</p>
                `;
                return;
            }

            // Check if quiz started
            if (!config.quiz_started) {
                app.innerHTML = `
                    <div class="intro-title">${data.event.title}</div>
                    <div class="intro-desc mt-4">Kuis belum dimulai</div>
                `;
                return;
            }

            // Question Active
            const question = data.current_question;
            const state = config.question_state; // blurred, unblurred, revealed

            if (!question) {
                app.innerHTML = `<h1>Menunggu soal...</h1>`;
                return;
            }

            let optionsHtml = '';
            if (question.options) {
                question.options.forEach(opt => {
                    const isCorrectClass = (state === 'revealed' && opt.is_correct) ? 'correct' : '';
                    optionsHtml += `<div class="option-item ${isCorrectClass}">${opt.label}. ${opt.option_text}</div>`;
                });
            }

            const blurClass = (state === 'blurred') ? 'blurred' : '';
            
            app.innerHTML = `
                <div class="mb-4">Soal Ke-${config.current_question_seq}</div>
                <div class="timer" id="timer-display">10</div>
                <div class="${blurClass}">
                    <div class="question-text">${question.question_text}</div>
                    <div class="options-container">
                        ${optionsHtml}
                    </div>
                </div>
            `;

            // Manage Timer
            if (state === 'unblurred') {
                startLocalTimer(config.timer_started_at, config.timer_stopped_at);
            } else if (state === 'revealed' || state === 'blurred') {
                clearInterval(timerInterval);
                const timerDisplay = document.getElementById('timer-display');
                if (timerDisplay) {
                    if (config.timer_stopped_at && config.timer_started_at) {
                        const diff = config.timer_stopped_at - config.timer_started_at;
                        timerDisplay.innerText = Math.max(0, 10 - diff);
                    } else {
                        timerDisplay.innerText = state === 'blurred' ? '10' : '0';
                    }
                }
            }
        }

        function startLocalTimer(startedAt, stoppedAt) {
            clearInterval(timerInterval);
            const timerDisplay = document.getElementById('timer-display');
            
            const tick = () => {
                const now = Math.floor(Date.now() / 1000);
                const end = stoppedAt || now;
                const elapsed = end - startedAt;
                const remaining = Math.max(0, 10 - elapsed);
                
                if (timerDisplay) {
                    timerDisplay.innerText = remaining;
                }

                if (remaining <= 0 || stoppedAt) {
                    clearInterval(timerInterval);
                }
            };

            tick();
            if (!stoppedAt) {
                timerInterval = setInterval(tick, 1000);
            }
        }

        // Poll every 2 seconds
        setInterval(fetchState, 2000);
        fetchState();
    </script>
</body>
</html>
