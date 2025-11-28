<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}
include_once '../autoloader.php';
include '../includes/quizOverview.inc.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quiz overview</title>
    <link rel="stylesheet" href="../styles.css?v=<?= time(); ?>">
</head>
<body>
    <div class="background"></div>
    <?php include 'header.php';?>

    <div class="quizOverviewWrapper">
        <div class="quizOverview">
            <a href="createQuiz.page.php?type=quizName" id="quizName">Editing quiz: <?= $quizName ?></a>
            <?php foreach ($questions as $q): ?>
                <div class="question-row"
                     data-url="createQuiz.page.php?id=<?= $q['Id'] ?>&type=<?= $q['IdAnswerType'] ?>">

                    <div class="question-info">
                        <div>Question: <?= htmlspecialchars($q['Text']) ?></div>
                        <div>Type: <?= htmlspecialchars($q['Type']) ?></div>
                    </div>

                    <a href="../includes/deleteQuestion.inc.php?id=<?= $q['Id'] ?>"
                       class="delete-btn btnDel2"
                       onclick="event.stopPropagation();">
                        Delete
                    </a>

                </div>
            <?php endforeach; ?>
        </div>
        <a href="quizes.page.php" class='saveClose'>Save & close</a>
    </div>
    <footer>© 2025 Lukas Kopecky. All rights reserved.</footer>
    <script>
        document.querySelectorAll('.question-row').forEach(row => {
            row.addEventListener('click', () => {
                window.location.href = row.dataset.url;
            });
        });
    </script>
</body>
</html>
