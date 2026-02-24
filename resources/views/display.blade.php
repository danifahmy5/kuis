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
            padding: 110px 24px 24px;
        }

        .display-layout {
            width: min(1400px, 96vw);
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(280px, 360px);
            gap: 24px;
            align-items: start;
        }

        .display-topbar {
            position: fixed;
            top: 16px;
            left: 16px;
            right: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 30;
            pointer-events: none;
        }

        .branding-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(10, 46, 92, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            pointer-events: auto;
        }

        .branding-badge img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
        }

        .branding-text {
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #fff;
            text-transform: uppercase;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            pointer-events: auto;
        }

        .topbar-btn {
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            border-radius: 999px;
            padding: 10px 16px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .topbar-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .leaderboard-panel {
            width: 100%;
            max-height: calc(100vh - 140px);
            overflow-y: auto;
            background: rgba(7, 20, 42, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 18px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 18px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
        }

        .leaderboard-panel h5 {
            margin-bottom: 14px;
            font-weight: 700;
            color: var(--primary-gold);
        }

        .leaderboard-item {
            display: grid;
            grid-template-columns: 44px 1fr auto;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.06);
            margin-bottom: 8px;
            transition: transform 0.4s ease, background 0.4s ease, box-shadow 0.4s ease;
        }

        .rank-chip {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            background: rgba(255, 215, 0, 0.2);
            color: #ffe083;
        }

        .score-chip {
            font-weight: 700;
            color: #fff;
            background: rgba(25, 135, 84, 0.26);
            border: 1px solid rgba(67, 170, 121, 0.4);
            border-radius: 999px;
            padding: 4px 12px;
        }

        .leaderboard-empty {
            color: #d1d8e3;
            text-align: center;
            margin: 8px 0 2px;
        }

        .leaderboard-item.moved {
            animation: leaderboardMove 0.6s ease;
            background: rgba(255, 215, 0, 0.12);
            box-shadow: 0 0 18px rgba(255, 215, 0, 0.2);
        }

        .d-none {
            display: none !important;
        }

        .display-footer {
            position: fixed;
            bottom: 14px;
            right: 18px;
            z-index: 25;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 0.78rem;
            letter-spacing: 0.4px;
            color: rgba(232, 238, 249, 0.75);
            padding: 4px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            text-shadow: 0 1px 8px rgba(0, 0, 0, 0.35);
            opacity: 0.9;
        }

        .display-footer i {
            font-size: 0.72rem;
            opacity: 0.85;
        }

        .display-footer strong {
            color: rgba(255, 255, 255, 0.92);
            font-weight: 600;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            padding: 4.5rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            width: 100%;
            text-align: center;
            position: relative;
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
            font-size: clamp(3.4rem, 6vw, 5.4rem);
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

        .question-text {
            font-size: clamp(2.2rem, 4.2vw, 3.8rem);
            margin-bottom: 36px;
            font-weight: 600;
            line-height: 1.3;
        }

        .options-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .option-item {
            background: rgba(255, 255, 255, 0.9);
            color: #0a2e5c;
            padding: 24px 24px;
            font-size: clamp(1.3rem, 2.2vw, 2rem);
            line-height: 1.35;
            font-weight: 600;
            border-radius: 12px;
            text-align: left;
            transition: all 0.3s;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
        }

        .option-item.correct {
            background: #198754;
            color: #fff;
        }

        .wrong-feedback {
            animation: shake 0.5s ease-in-out;
        }

        .wrong-feedback::before {
            content: "";
            position: absolute;
            inset: -8px;
            border-radius: 28px;
            border: 1px solid rgba(255, 85, 85, 0.45);
            box-shadow: 0 0 0 0 rgba(255, 85, 85, 0.3);
            animation: redPulse var(--wrong-feedback-duration, 1.6s) ease-in-out 1;
            pointer-events: none;
        }

        .wrong-feedback .option-item {
            animation: redTint var(--wrong-feedback-duration, 1.6s) ease-in-out 1;
        }

        .intro-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(3.2rem, 5.5vw, 5rem);
            font-weight: 700;
        }

        .intro-desc {
            font-size: clamp(1.6rem, 3vw, 2.6rem);
            color: #b8c6db;
        }

        .sound-gate {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(4, 10, 24, 0.72);
            padding: 24px;
        }

        .sound-gate-card {
            width: min(560px, 100%);
            border-radius: 18px;
            text-align: center;
            padding: 32px 26px;
            background: rgba(10, 46, 92, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.38);
        }

        .sound-gate-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(1.8rem, 3.8vw, 2.8rem);
            margin-bottom: 12px;
            color: #fff;
        }

        .sound-gate-text {
            margin: 0;
            font-size: 1.02rem;
            color: #d6e1f3;
        }

        .sound-gate-btn {
            margin-top: 22px;
            border: 0;
            border-radius: 999px;
            padding: 12px 24px;
            font-weight: 700;
            color: #0a2e5c;
            background: var(--primary-gold);
            transition: transform 0.15s ease;
        }

        .sound-gate-btn:hover {
            transform: translateY(-1px);
        }

        @media (min-width: 1366px) {
            .glass-card {
                padding: 5rem 5.25rem;
            }

            .countdown-number {
                font-size: 3.4rem;
            }

            .countdown-label {
                font-size: 0.95rem;
            }
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

        @keyframes leaderboardMove {
            0% { transform: scale(1); }
            35% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            20% { transform: translateX(-10px); }
            40% { transform: translateX(10px); }
            60% { transform: translateX(-8px); }
            80% { transform: translateX(8px); }
            100% { transform: translateX(0); }
        }

        @keyframes redPulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 85, 85, 0.2); }
            50% { box-shadow: 0 0 40px 8px rgba(255, 85, 85, 0.45); }
            100% { box-shadow: 0 0 0 0 rgba(255, 85, 85, 0.2); }
        }

        @keyframes redTint {
            0% { box-shadow: 0 0 0 rgba(255, 85, 85, 0); }
            50% { box-shadow: 0 0 30px rgba(255, 85, 85, 0.45); }
            100% { box-shadow: 0 0 0 rgba(255, 85, 85, 0); }
        }

        @media (max-width: 768px) {
            .event-title { font-size: 2.5rem; }
            .glass-card { padding: 2rem; width: 95%; }
            .options-container { grid-template-columns: 1fr; }
            .question-text { font-size: 1.6rem; }
            .intro-title { font-size: 2.5rem; }
            .intro-desc { font-size: 1.4rem; }
            .countdown-container { gap: 1rem; }
            .countdown-number { font-size: 2rem; }
            .display-topbar {
                top: 12px;
                left: 12px;
                right: 12px;
            }
            .branding-text {
                display: none;
            }
            .topbar-btn {
                padding: 8px 12px;
                font-size: 0.82rem;
            }
            .display-layout {
                grid-template-columns: 1fr;
            }
            .display-footer {
                right: 12px;
                bottom: 10px;
                font-size: 0.72rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-container">
        <div class="bg-overlay"></div>
    </div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="display-topbar">
        <div class="branding-badge">
            <img src="{{ asset('logo.png') }}" alt="Logo">
            <span class="branding-text">Quiz Arena</span>
        </div>
        <div class="topbar-actions">
            <button type="button" class="topbar-btn" id="sound-toggle">
                <i class="fas fa-volume-mute me-1"></i>Unmute
            </button>
            <button type="button" class="topbar-btn" id="fullscreen-toggle">
                <i class="fas fa-expand me-1"></i>Fullscreen
            </button>
        </div>
    </div>

    <div class="main-content">
        <div class="display-layout">
            <div class="glass-card" id="app">
                {{-- Content will be updated by JavaScript --}}
                <div class="status-badge">
                    <div class="status-dot"></div>
                    Live Preview
                </div>
                <h1 class="event-title">Loading...</h1>
            </div>
            <div class="leaderboard-panel" id="leaderboard-panel">
                <h5><i class="fas fa-ranking-star me-2"></i>Leaderboard</h5>
                <div id="leaderboard-list"></div>
            </div>
        </div>
    </div>

    <div class="display-footer">
        <i class="fas fa-sparkles"></i>
        crafted by <strong>danifahmy5</strong>
    </div>

    <div class="sound-gate" id="sound-gate">
        <div class="sound-gate-card">
            <div class="sound-gate-title">Nyalakan Suara</div>
            <p class="sound-gate-text">Klik tombol di bawah untuk mengaktifkan audio kuis.</p>
            <button type="button" class="sound-gate-btn" id="sound-gate-unmute">
                <i class="fas fa-volume-up me-1"></i>Unmute
            </button>
        </div>
    </div>

    <script>
        const eventId = {{ $event->id }};
        let lastState = null;
        let countdownInterval = null;
        const leaderboardPanel = document.getElementById('leaderboard-panel');
        const leaderboardList = document.getElementById('leaderboard-list');
        const fullscreenToggle = document.getElementById('fullscreen-toggle');
        const soundToggle = document.getElementById('sound-toggle');
        const soundGate = document.getElementById('sound-gate');
        const soundGateUnmute = document.getElementById('sound-gate-unmute');
        const heartbeatLateSrc = "{{ asset('heartbeat-02.mp3') }}";
        const wrongAnswerSrc = "{{ asset('wrong-answer.mp3') }}";
        
        const audioTracks = {
            late: new Audio(heartbeatLateSrc),
        };
        const wrongAnswerAudio = new Audio(wrongAnswerSrc);
        let currentAudioKey = null;
        let audioEnabled = false;
        let audioMuted = true;
        let lastAudioContext = { shouldPlay: false, key: null };
        let currentQuestionKey = null;
        let lastWrongMarkAt = null;
        let lastWrongQuestionKey = null;
        let lastLeaderboardOrder = new Map();
        let wrongFeedbackInitialized = false;

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
            renderLeaderboard(data.leaderboard || []);
            
            // Check if intro
            if (data.event.is_intro) {
                clearCountdown();
                stopAudio(true);
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
                stopAudio(true);
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
                stopAudio(true);
                resetBuzzerTracking(null);
                app.innerHTML = `<h1>Menunggu soal...</h1>`;
                return;
            }

            const nextQuestionKey = question.id ?? data.event.current_question_seq;
            if (nextQuestionKey !== currentQuestionKey) {
                resetBuzzerTracking(nextQuestionKey);
                lastWrongQuestionKey = nextQuestionKey;
                lastWrongMarkAt = data.wrong_answer?.marked_at || null;
                wrongFeedbackInitialized = true;
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
                <div class="mb-4">Soal Ke-${data.event.current_question_seq}</div>
                <div class="${blurClass}">
                    <div class="question-text">${question.question_text}</div>
                    <div class="options-container">
                        ${optionsHtml}
                    </div>
                </div>
            `;
            clearCountdown();

            handleWrongFeedback(data.wrong_answer, nextQuestionKey);

            if (state === 'unblurred') {
                setActiveAudio('late', true);
                lastAudioContext = { shouldPlay: true, key: 'late' };
            } else if (state === 'revealed' || state === 'blurred') {
                stopAudio(true);
            }
        }

        function renderLeaderboard(leaderboard) {
            if (!leaderboardList) return;

            if (!leaderboard.length) {
                leaderboardList.innerHTML = `<div class="leaderboard-empty">Belum ada skor peserta.</div>`;
                lastLeaderboardOrder = new Map();
                return;
            }

            leaderboardList.innerHTML = leaderboard.map((item, index) => {
                const teamText = item.team_name ? `<small class="text-white-50 d-block">${item.team_name}</small>` : '';
                const previousIndex = lastLeaderboardOrder.get(item.id);
                const movedClass = typeof previousIndex === 'number' && previousIndex !== index ? 'moved' : '';
                return `
                    <div class="leaderboard-item ${movedClass}">
                        <div class="rank-chip">${index + 1}</div>
                        <div>
                            <div class="fw-bold">${item.name}</div>
                            ${teamText}
                        </div>
                        <div class="score-chip">${item.total_points} pts</div>
                    </div>
                `;
            }).join('');

            lastLeaderboardOrder = new Map(
                leaderboard.map((item, index) => [item.id, index])
            );
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

        function isFullscreenActive() {
            return Boolean(document.fullscreenElement);
        }

        function updateFullscreenButton() {
            if (!fullscreenToggle) return;
            if (isFullscreenActive()) {
                fullscreenToggle.innerHTML = '<i class="fas fa-compress me-1"></i>Exit Fullscreen';
            } else {
                fullscreenToggle.innerHTML = '<i class="fas fa-expand me-1"></i>Fullscreen';
            }
        }

        function setupAudio() {
            Object.values(audioTracks).forEach(track => {
                track.loop = true;
                track.preload = 'auto';
            });
            wrongAnswerAudio.preload = 'auto';
        }

        function updateSoundButton() {
            if (!soundToggle) return;
            if (audioMuted) {
                soundToggle.innerHTML = '<i class="fas fa-volume-mute me-1"></i>Unmute';
            } else {
                soundToggle.innerHTML = '<i class="fas fa-volume-up me-1"></i>Mute';
            }
        }

        function closeSoundGate() {
            if (!soundGate) return;
            soundGate.classList.add('d-none');
        }

        function openSoundGate() {
            if (!soundGate) return;
            soundGate.classList.remove('d-none');
        }

        function setSoundMuted(nextMuted) {
            if (!audioEnabled) {
                audioEnabled = true;
            }

            audioMuted = nextMuted;
            updateSoundButton();
            Object.values(audioTracks).forEach(track => {
                track.muted = audioMuted;
            });
            wrongAnswerAudio.muted = audioMuted;

            if (audioMuted) {
                pauseAllAudio(false);
            } else if (lastAudioContext.shouldPlay && lastAudioContext.key) {
                setActiveAudio(lastAudioContext.key, false);
            }
        }

        function canPlayAudio() {
            return audioEnabled && !audioMuted;
        }

        function pauseAllAudio(reset = false) {
            Object.values(audioTracks).forEach(track => {
                track.pause();
                if (reset) {
                    track.currentTime = 0;
                }
            });
            if (reset) {
                currentAudioKey = null;
            }
        }

        function setActiveAudio(key, resetOnSwitch = true) {
            if (currentAudioKey === key) {
                if (canPlayAudio()) {
                    audioTracks[key].play().catch(() => {});
                }
                return;
            }

            if (resetOnSwitch) {
                pauseAllAudio(true);
            } else {
                pauseAllAudio(false);
            }

            currentAudioKey = key;
            if (canPlayAudio()) {
                audioTracks[key].play().catch(() => {});
            }
        }

        function stopAudio(reset = true) {
            lastAudioContext = { shouldPlay: false, key: null };
            pauseAllAudio(reset);
        }

        function resetBuzzerTracking(questionKey) {
            currentQuestionKey = questionKey;
        }

        function handleWrongFeedback(wrongAnswer, questionKey) {
            if (!wrongAnswer || !wrongAnswer.marked_at) return;
            if (lastWrongQuestionKey !== questionKey) {
                lastWrongQuestionKey = questionKey;
                lastWrongMarkAt = wrongAnswer.marked_at;
                wrongFeedbackInitialized = true;
                return;
            }

            if (!wrongFeedbackInitialized) {
                lastWrongMarkAt = wrongAnswer.marked_at;
                wrongFeedbackInitialized = true;
                return;
            }

            if (lastWrongMarkAt && wrongAnswer.marked_at <= lastWrongMarkAt) {
                return;
            }

            lastWrongMarkAt = wrongAnswer.marked_at;
            triggerWrongFeedback();
        }

        function triggerWrongFeedback() {
            const app = document.getElementById('app');
            if (!app) return;
            const fallbackDuration = 1600;
            let effectDurationMs = fallbackDuration;
            if (!Number.isNaN(wrongAnswerAudio.duration) && Number.isFinite(wrongAnswerAudio.duration) && wrongAnswerAudio.duration > 0) {
                effectDurationMs = Math.max(600, Math.round(wrongAnswerAudio.duration * 1000));
            }
            app.style.setProperty('--wrong-feedback-duration', `${effectDurationMs}ms`);
            app.classList.remove('wrong-feedback');
            void app.offsetWidth;
            app.classList.add('wrong-feedback');
            if (canPlayAudio()) {
                wrongAnswerAudio.currentTime = 0;
                wrongAnswerAudio.play().catch(() => {});
                const handleEnded = () => {
                    app.classList.remove('wrong-feedback');
                    wrongAnswerAudio.removeEventListener('ended', handleEnded);
                };
                wrongAnswerAudio.addEventListener('ended', handleEnded);
            }
            window.setTimeout(() => {
                app.classList.remove('wrong-feedback');
            }, effectDurationMs);
        }

        if (fullscreenToggle) {
            fullscreenToggle.addEventListener('click', async () => {
                try {
                    if (isFullscreenActive()) {
                        await document.exitFullscreen();
                    } else {
                        await document.documentElement.requestFullscreen();
                    }
                } catch (error) {
                    console.error('Fullscreen toggle failed:', error);
                } finally {
                    updateFullscreenButton();
                }
            });
        }

        if (soundToggle) {
            soundToggle.addEventListener('click', () => {
                setSoundMuted(!audioMuted);
            });
        }

        if (soundGateUnmute) {
            soundGateUnmute.addEventListener('click', () => {
                setSoundMuted(false);
                closeSoundGate();
            });
        }

        document.addEventListener('fullscreenchange', updateFullscreenButton);
        updateFullscreenButton();
        setupAudio();
        updateSoundButton();
        openSoundGate();

        // Poll every 2 seconds
        setInterval(fetchState, 2000);
        fetchState();
    </script>
</body>
</html>
