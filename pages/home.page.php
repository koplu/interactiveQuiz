<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

if (!isset($_SESSION['user'])) { 
    header("Location: ../index.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Homepage</title>
    <link rel="stylesheet" href="../styles.css?v=<?= time(); ?>">
</head>
<body>
    <?php include 'header.php' ?>
    <div class="background"></div>
    <div class="centered-cont">
        <div class="home-content">
            <div class="welcome-text">Welcome <?php echo $_SESSION["user"] ?></div>
            <div class="home-links">
                <a href="createQuiz.page.php?type=quizName&act=new">Create quiz</a>
                <a href="quizes.page.php">My quizzes</a>
            </div>
        </div>
    </div>
    <footer>© 2025 Lukas Kopecky. All rights reserved.</footer>
</body>
</html>