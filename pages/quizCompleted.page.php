<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quiz completed</title>
    <link rel="stylesheet" href="../styles.css?v=<?= time(); ?>">
</head>
<body>
    <div class="background"></div>
    <div class="completed-box">
        <h2 class="completed-title">You have already completed this quiz</h2>
        <p class="completed-text">
            If your teacher resets the quiz results, you will be able to retake it.
        </p>

        <a class="retry-btn" href="takeQuiz.page.php?idQuiz=<?= $_GET['idQuiz'] ?>&user=anonymous">
            Try again
        </a>
    </div>
    <footer>© 2025 Lukas Kopecky. All rights reserved.</footer>
</body>
</html>