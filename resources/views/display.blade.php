<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .question-container {
            text-align: center;
            margin-top: 100px;
        }
        .leaderboard-container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="question-container">
            {{-- Current question will be displayed here --}}
            <h1>Question placeholder</h1>
        </div>

        <div class="leaderboard-container">
            <h2>Leaderboard</h2>
            {{-- Leaderboard will be displayed here --}}
        </div>
    </div>
</body>
</html>
