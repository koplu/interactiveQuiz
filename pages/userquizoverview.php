<?php 
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

include '../autoloader.php';
include '../includes/userquizoverview.inc.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Results</title>
    <link rel="stylesheet" href="../styles.css?v=<?= time(); ?>">
</head>
<body>
    <div class="background"></div>
    <div class="quizOverviewWrapper">
        <div class="quizOverview">
            <div class="resultsHeader">
                <div class="resultsTitle">Results for: <?= htmlspecialchars($quizName) ?></div>

                <div class="scoreBox">
                    <span class="scoreLabel">Score:</span>
                    <span class="scoreValue"><?= $correct ?> / <?= $total ?></span>
                </div>

                <div class="rankBox">
                    <span class="rankLabel">Your rank:</span>
                    <span class="rankValue"><?= $userRank ?> / <?= count($quizResults) ?></span>
                </div>
            </div>
            <button class="show-leaderboard-btn" id="showLeaderboard">Show Leaderboard</button>
            <?php foreach ($results as $r): ?>
                <div class="questionBox <?= $r['isCorrect'] ? 'correct' : 'wrong' ?>">
                    <div class="questionText"><?= htmlspecialchars($r['questionText']) ?></div>
                    <div class="result">
                        <?= $r['isCorrect'] ? "Correct" : "Wrong" ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="leaderboard-popup" id="leaderboardPopup">
        <div class="leaderboard-content">
            <span id="leaderboard-close">&times;</span>
            <h2>Leaderboard</h2>
            <table>
                <tr>
                    <th>Rank</th>
                    <th>User</th>
                    <th>Score</th>
                    <th>Percentage</th>
                </tr>
                <?php foreach ($quizResults as $i => $userData): ?>
                    <tr <?= $userData['IdUser'] == $userId ? 'style="background-color:#d4f7d4;"' : '' ?>>
                        <td><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($userData['Username']) ?></td>
                        <td><?= $userData['score'] ?>/<?= $total ?></td>
                        <td><?= round($userData['score']/$total*100) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>


    <footer>© 2025 Lukas Kopecky. All rights reserved.</footer>


    <script>
        const showBtn = document.getElementById('showLeaderboard');
        const popup = document.getElementById('leaderboardPopup');
        const closeBtn = document.getElementById('leaderboard-close');

        showBtn.addEventListener('click', () => popup.style.display = 'flex');
        closeBtn.addEventListener('click', () => popup.style.display = 'none');

        popup.addEventListener('click', (e) => {
            if(e.target === popup) popup.style.display = 'none';
        });
    </script>
</body>
</html>