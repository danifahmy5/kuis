<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} - Quiz Display</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Montserrat:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gold: #FFD700;
            --secondary-blue: #0a2e5c;
            --accent-glow: rgba(255, 215, 0, 0.6);
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            overflow: hidden;
            background-color: #000;
            color: #fff;
        }

        .bg-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image: url('https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?q=80&w=2969&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        .bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(10, 25, 47, 0.95) 0%, rgba(32, 58, 67, 0.85) 100%);
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            animation: float 10s infinite ease-in-out;
        }
        .orb-1 {
            top: -10%;
            left: -10%;
            width: 50vw;
            height: 50vw;
            background: rgba(44, 62, 80, 0.5);
        }
        .orb-2 {
            bottom: -20%;
            right: -10%;
            width: 40vw;
            height: 40vw;
            background: rgba(10, 46, 92, 0.6);
        }

        .main-content {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 10;
            padding: 24px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            padding: 4rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            max-width: 1000px;
            width: 90%;
            text-align: center;
            animation: slideUp 1.2s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .event-subtitle {
            text-transform: uppercase;
            letter-spacing: 4px;
            color: #b8c6db;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .event-title {
            font-family: 'Cinzel', serif;
            font-size: 4rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1.5rem;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            line-height: 1.1;
        }

        .event-title span {
            color: var(--primary-gold);
            text-shadow: 0 0 25px var(--accent-glow);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            border-radius: 50px;
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: #ff6b6b;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 2rem;
        }

        .status-dot {
            width: 12px;
            height: 12px;
            background-color: #ff6b6b;
            border-radius: 50%;
            animation: pulse-red 2s infinite;
        }

        .countdown-container {
            display: flex;
            justify-content: center;
            gap: 2rem;
        }

        .countdown-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .countdown-number {
            font-family: 'Montserrat', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        .countdown-label {
            font-size: 0.8rem;
            color: #8fa3bf;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
        }

        .blurred {
            filter: blur(20px);
            pointer-events: none;
            user-select: none;
        }

        .timer {
            font-size: 5rem;
            font-weight: 800;
            margin: 20px 0;
            color: var(--primary-gold);
            text-shadow: 0 0 18px rgba(255, 215, 0, 0.25);
        }

        .question-text {
            font-size: 2.5rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .options-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .option-item {
            background: rgba(255, 255, 255, 0.9);
            color: #0a2e5c;
            padding: 18px 20px;
            font-size: 1.35rem;
            border-radius: 12px;
            text-align: left;
            transition: all 0.3s;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
        }

        .option-item.correct {
            background: #198754;
            color: #fff;
        }

        .intro-title {
            font-family: 'Cinzel', serif;
            font-size: 4rem;
            font-weight: 700;
        }

        .intro-desc {
            font-size: 2rem;
            color: #b8c6db;
        }

        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 107, 107, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 107, 107, 0); }
        }

        @keyframes float {
            0% { transform: translate(0, 0); }
            50% { transform: translate(20px, 40px); }
            100% { transform: translate(0, 0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .event-title { font-size: 2.5rem; }
            .glass-card { padding: 2rem; width: 95%; }
            .options-container { grid-template-columns: 1fr; }
            .timer { font-size: 3rem; }
            .question-text { font-size: 1.6rem; }
            .intro-title { font-size: 2.5rem; }
            .intro-desc { font-size: 1.4rem; }
            .countdown-container { gap: 1rem; }
            .countdown-number { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <div class="bg-container">
        <div class="bg-overlay"></div>
    </div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="main-content">
        <div class="glass-card" id="app">
            {{-- Content will be updated by JavaScript --}}
            <div class="status-badge">
                <div class="status-dot"></div>
                Live Preview
            </div>
            <h1 class="event-title">Loading...</h1>
        </div>
    </div>

    <script>
        const eventId = {{ $event->id }};
        let lastState = null;
        let timerInterval = null;
        let countdownInterval = null;

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
            
            // Check if intro
            if (data.event.is_intro) {
                clearCountdown();
                app.innerHTML = `
                    <div class="intro-title">${data.event.title}</div>
                    <div class="intro-desc mt-4">${data.event.title}</div>
                    <p class="mt-5">Persiapan dimulai...</p>
                `;
                return;
            }

            // Check if quiz started
            if (!data.event.quiz_started) {
                const startedAt = data.event.started_at ? new Date(data.event.started_at) : null;
                app.innerHTML = `
                    <div class="intro-title">${data.event.title}</div>
                    <div class="intro-desc mt-4">Kuis belum dimulai</div>
                    <div class="countdown-container mt-4" id="countdown">
                        <div class="countdown-item">
                            <span class="countdown-number" id="minutes">00</span>
                            <span class="countdown-label">Menit</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number">:</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-number" id="seconds">00</span>
                            <span class="countdown-label">Detik</span>
                        </div>
                    </div>
                    <p class="text-white-50 mt-3" id="countdown-note"></p>
                `;
                startCountdown(startedAt);
                return;
            }

            // Question Active
            const question = data.current_question;
            const state = data.event.question_state; // blurred, unblurred, revealed

            if (!question) {
                clearCountdown();
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
            
            const duration = question.duration || 10;

            app.innerHTML = `
                <div class="mb-4">Soal Ke-${data.event.current_question_seq}</div>
                <div class="timer" id="timer-display">${duration}</div>
                <div class="${blurClass}">
                    <div class="question-text">${question.question_text}</div>
                    <div class="options-container">
                        ${optionsHtml}
                    </div>
                </div>
            `;
            clearCountdown();

            // Manage Timer
            if (state === 'unblurred') {
                startLocalTimer(data.event.timer_started_at, data.event.timer_stopped_at, duration);
            } else if (state === 'revealed' || state === 'blurred') {
                clearInterval(timerInterval);
                const timerDisplay = document.getElementById('timer-display');
                if (timerDisplay) {
                    if (data.event.timer_stopped_at && data.event.timer_started_at) {
                        const diff = data.event.timer_stopped_at - data.event.timer_started_at;
                        timerDisplay.innerText = Math.max(0, duration - diff);
                    } else {
                        timerDisplay.innerText = state === 'blurred' ? String(duration) : '0';
                    }
                }
            }
        }

        function startLocalTimer(startedAt, stoppedAt, duration) {
            clearInterval(timerInterval);
            const timerDisplay = document.getElementById('timer-display');
            
            const tick = () => {
                const now = Math.floor(Date.now() / 1000);
                const end = stoppedAt || now;
                const elapsed = end - startedAt;
                const remaining = Math.max(0, duration - elapsed);
                
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

        function clearCountdown() {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }

        function startCountdown(startedAt) {
            clearCountdown();
            const minutesSpan = document.getElementById('minutes');
            const secondsSpan = document.getElementById('seconds');
            const note = document.getElementById('countdown-note');

            if (!minutesSpan || !secondsSpan) return;
            if (!startedAt || Number.isNaN(startedAt.getTime())) {
                if (note) note.textContent = 'Waktu mulai belum ditentukan.';
                minutesSpan.textContent = '00';
                secondsSpan.textContent = '00';
                return;
            }

            const update = () => {
                const now = new Date();
                const diff = startedAt.getTime() - now.getTime();
                if (diff <= 0) {
                    minutesSpan.textContent = '00';
                    secondsSpan.textContent = '00';
                    if (note) note.textContent = 'Menunggu host memulai...';
                    clearCountdown();
                    return;
                }
                const totalSeconds = Math.floor(diff / 1000);
                const minutes = Math.floor((totalSeconds / 60) % 60);
                const seconds = totalSeconds % 60;
                minutesSpan.textContent = String(minutes).padStart(2, '0');
                secondsSpan.textContent = String(seconds).padStart(2, '0');
                if (note) note.textContent = `Mulai pada ${startedAt.toLocaleString('id-ID')}`;
            };

            update();
            countdownInterval = setInterval(update, 1000);
        }

        // Poll every 2 seconds
        setInterval(fetchState, 2000);
        fetchState();
    </script>
</body>
</html>
