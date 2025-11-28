<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css?v=<?= time(); ?>">
    <script defer src="scripts/validateLogin.js?v=<?= time(); ?>"></script>
</head>
<body class='login-page'>
    <div class="background"></div>
    <div class="centered-cont">
        <form class="login-form" action="includes/login.inc.php" method="post">
            <div>Login</div>
            <div class="login-form-fields">
                <input id="uid" type="text" name="uid" placeholder="Username">
                <input id="pwd" type="password" name="pwd" placeholder="Password">
                <button type="submit" name="submit">Login</button>
                <div id="login-error"></div>
                <div>Don't have an account?</div>
                <a href="pages/signup.page.php">Sign up</a>
            </div>
        </form>
    </div>
    <footer>© 2025 Lukas Kopecky. All rights reserved.</footer>
</body>
</html>