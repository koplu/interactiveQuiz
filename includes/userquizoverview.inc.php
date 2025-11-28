<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

include_once '../autoloader.php';

$user = null;
if(isset($_SESSION['anynomous'])){
    $user = $_SESSION['anynomous'];
}

$questionsAnswersControler = new QuestionsAnswers();
$questions = $questionsAnswersControler->getAllQuestions();
$quizName = $questionsAnswersControler->getQuizbyId()['Name'];

$anonymCont = new anonymous();
$userId = $anonymCont->getUserIdd($_SESSION['anonymous'])['Id'];

$results = $questionsAnswersControler->getAllUserAnswerss($userId, $_SESSION['quizId']);
$total = count($results);
$correct = 0;
foreach ($results as $r) {
    if ($r['isCorrect']) $correct++;
}
$quizResults = $questionsAnswersControler->getAllQuizResultss(null);
$rank = 1;
$userRank = null;
foreach ($quizResults as $row) {
    if ($row['IdUser'] == $userId) {
        $userRank = $rank;
        break;
    }
    $rank++;
}