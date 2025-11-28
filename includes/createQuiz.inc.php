<?php
if (isset($_POST['submit'])){

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $uid = $_POST['uid'];
    $pwd = $_POST['pwd'];

    include '../autoloader.php';

    $signup = new Signup($name,$surname,$uid,$pwd);
    $signup->signUpUser();
}
