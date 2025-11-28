<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include '../autoloader.php';

    $quizId = $_GET['idQuiz'] ?? null;
    $username = $_POST['name'] ?? null;

    $anonymous = new anonymous();
    $anonymous->newAnonymous($username);
    $userId = $anonymous->getUserIdd($_POST['name'])['Id'];

    $questionsAnswersControler = new QuestionsAnswers();
    if($questionsAnswersControler->hasUserCompletedQuizz($userId ,$quizId)){
        header("location: ../pages/quizCompleted.page.php?idQuiz=$quizId");
        exit();
    }

    $_SESSION['quizId'] = $quizId;
    
    header("location: ../pages/takeQuiz.page.php?idQuiz=$quizId&pos=1");
}