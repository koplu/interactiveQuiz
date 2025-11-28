<?php

if (isset($_POST['submit'])){

    $uid = $_POST['uid'];
    $pwd = $_POST['pwd'];

    include '../autoloader.php';
    $login = new Login($uid, $pwd);

    $login->loginUser();

    header("location: ../pages/home.page.php");
}