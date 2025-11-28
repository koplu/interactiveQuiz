<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

include_once '../autoloader.php';

if(isset($_GET['id'])){
    $_SESSION['quizId'] = $_GET['id'];
}

$questionsAnswersControler = new QuestionsAnswers();
$questions = $questionsAnswersControler->getAllQuestions();
$quizName = $questionsAnswersControler->getQuizbyId()['Name'];
