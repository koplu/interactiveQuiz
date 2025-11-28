<?php 
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}
include '../autoloader.php';
include '../includes/teacherQuizOverview.inc.php';
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
    <?php include 'header.php'; ?>
    <div class="quizOverviewWrapper teacherView">

        <h2 class="leaderboardTitle">
            <div>
                Leaderboard – <?= htmlspecialchars($quizName) ?>&nbsp
            </div>
            <a href="../includes/deleteQuizProgress.inc.php?idQuiz=<?= $idQuiz ?>"> / Delete progress</a>
        </h2>

        <table class="leaderboardTable">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>Score</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($quizResults as $i => $user): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($user['Username']) ?></td>
                    <td><?= $user['score'] ?> / <?= $total ?></td>
                    <td>
                        <button class="detailsBtn" data-target="details-<?= $user['IdUser'] ?>">
                            View
                        </button>
                    </td>
                </tr>

                <tr id="details-<?= $user['IdUser'] ?>" class="detailsRow">
                    <td colspan="6">
                        <div class="detailsContent">
                            <?php foreach ($user['answers'] as $a): ?>
                                <div class="answerItem <?= $a['isCorrect'] ? 'correct' : 'wrong' ?>">
                                    <div class="questionTextSmall">
                                        <?= htmlspecialchars($a['questionText']) ?>
                                    </div>
                                    <div class="answerStatus">
                                        <?= $a['isCorrect'] ? "✔ Correct" : "✖ Wrong" ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>

            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer>© 2025 Lukas Kopecky. All rights reserved.</footer>


    <script>
        document.querySelectorAll(".detailsBtn").forEach(btn => {
            btn.addEventListener("click", () => {
                const target = document.getElementById(btn.dataset.target);
                target.style.display = 
                    target.style.display === "table-row" ? "none" : "table-row";
            });
        });
    </script>
</body>
</html>