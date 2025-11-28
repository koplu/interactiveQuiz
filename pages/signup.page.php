<?php 
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
include '../autoloader.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Signup</title>
    <link rel="stylesheet" href="../styles.css?v=<?= time(); ?>">
    <script defer src="../scripts/validateSignUp.js?v=<?= time(); ?>"></script>
</head>
<body>
    <div class="background"></div>
    <div class="centered-cont">
        <form class="login-form" action="../includes/signup.inc.php" method="post">
            <div>Sign Up</div>
            <div class="login-form-fields">
                <input id="name" type="text" name="name" placeholder="Name">
                <input id="surname" type="text" name="surname" placeholder="Surname">
                <input id="uid" type="text" name="uid" placeholder="Username">
                <input id="pwd" type="password" name="pwd" placeholder="Password">
                <input id="pwdrepeat" type="password" name="pwdrepeat" placeholder="Repeat password">
                <button id="btn1" type="submit" name="submit">Create account</button>
                <div id="signup-error"></div>
                <div>Already have an account?
                    <a href="../index.php">Login</a>
                </div>
            </div>
        </form>
    </div>
    <footer>© 2025 Lukas Kopecky. All rights reserved.</footer>
</body>
</html>