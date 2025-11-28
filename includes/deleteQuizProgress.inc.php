<?php
include_once '../autoloader.php';

if (isset($_GET['idQuiz'])){
    $questionsAnswersControler = new QuestionsAnswers();
    $idQuiz = $_GET['idQuiz'];

    $questionsAnswersControler->deleteQuizProgresss($idQuiz);
    header("location: ../pages/teacherQuizOverview.page.php?idQuiz=$idQuiz");
}
