<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
if (!isset($_SESSION['user'])) { 
    header("Location: ../index.php");
    exit();
}

include '../autoloader.php';

$quizCont = new createQuizName('default');
$quizCont->deleteQuizz($_GET['id']);

header("location: ../pages/quizes.page.php");
