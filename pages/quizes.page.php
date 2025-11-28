<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}
include_once '../autoloader.php';
include '../includes/quizes.inc.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quizes</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="../styles.css?v=<?= time(); ?>">
</head>
<body>
    <div class="background"></div>
    <?php include 'header.php';?>

    <div class="quizOverviewWrapper">
        <div class="quizOverview">
            <div id="quizName">Your quizzes</div>
            <?php foreach ($quizes as $q): ?>
                <div class="question-row" data-href="quizOverview.page.php?id=<?= $q['Id'] ?>">
                    <div class="qr-info">
                        <div class="qr-title"><?= $q['Name'] ?></div>
                        <div class="qr-date">Created: <?= $q['Date'] ?></div>
                    </div>

                    <div class="qr-actions">
                        <a href="teacherQuizOverview.page.php?idQuiz=<?= $q['Id']?>" 
                        class="action-btn no-row-click">Results</a>

                        <a href="takeQuiz.page.php?idQuiz=<?= $q['Id']?>&user=anonymous" 
                        class="action-btn no-row-click" target="_blank">Fill mode</a>

                        <button class="action-btn no-row-click qr-btn"
                                data-url="https://interactivequiz.infinityfreeapp.com/pages/takeQuiz.page.php?idQuiz=<?= $q['Id'] ?>&user=anonymous">
                            QR
                        </button>

                        <a href="../includes/deleteQuiz.inc.php?id=<?= $q['Id'] ?>" 
                        class="action-btn delete no-row-click">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <a href="createQuiz.page.php?type=quizName&act=new" class='saveClose'>New quiz</a>
        </div>
        <div id="qr-popup" class="qr-popup hidden">
            <div class="qr-popup-content">
                <span id="qr-close">&times;</span>
                <div id="qrcode"></div>
            </div>
        </div>
    </div>
    <footer>© 2025 Lukas Kopecky. All rights reserved.</footer>

    <script>
        const popup = document.getElementById("qr-popup");
        const qrContainer = document.getElementById("qrcode");
        const closeBtn = document.getElementById("qr-close");

        document.querySelectorAll(".qr-btn").forEach(btn => {
            btn.addEventListener("click", (e) => {
            const url = e.currentTarget.dataset.url;
            qrContainer.innerHTML = "";
            new QRCode(qrContainer, {
                text: url,
                width: 180,
                height: 180,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
            popup.classList.remove("hidden");
            });
        });

        popup.addEventListener("click", () => popup.classList.add("hidden"));
        closeBtn.addEventListener("click", () => popup.classList.add("hidden"));
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") popup.classList.add("hidden");
        });

        document.querySelectorAll(".question-row").forEach(row => {
            row.addEventListener("click", function(e) {
                if (!e.target.closest(".no-row-click")) {
                    window.location.href = this.dataset.href;
                }
            });
        });
    </script>
</body>
</html>
