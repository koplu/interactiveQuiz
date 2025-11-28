<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
if (isset($_SESSION['error'])){
    unset($_SESSION['error']);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['type'] == 'back') {

    include '../autoloader.php';
    if (isset($_SESSION['error'])){
        unset($_SESSION['error']);
    }
    
    $questionsAnswersCont = new QuestionsAnswers();
    $quizId = $_SESSION['quizId'];
    $totalCount = (int)$questionsAnswersCont->getQuizQuestionCountt($quizId);
    $lastQuestionId = $questionsAnswersCont->getQuestionIdByPositionn($totalCount);
    $lastQuestionType = $questionsAnswersCont->getQuestionTypeById($lastQuestionId)['IdAnswerType'];

    if($lastQuestionId == null){
        header("location: ../pages/createQuiz.page.php?type=quizName");
        exit;
    }

    header("location: ../pages/createQuiz.page.php?id=$lastQuestionId&type=$lastQuestionType");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['mode'] !== 'take') {

    include '../autoloader.php';
    if (isset($_SESSION['error'])){
        unset($_SESSION['error']);
    }
    
    $question = $_POST['question_text'];
    $questionId = $_POST['questionId'] ?? null;
    $rawAnswers = $_POST['answer'] ?? [];
    $rawOrders  = $_POST['order']  ?? [];
    $insert = false;

    $orderAnswerControler = new orderAnswer();

    if (!empty($questionId)){
        $orderAnswerControler->updateQuestionText($questionId,$question,$rawAnswers, $rawOrders);
    }
    else {
        $orderAnswerControler->insert($question,$rawAnswers, $rawOrders);
        $insert = true;
    }

    include_once 'navigation.inc.php';
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['mode'] == 'take'){
    include '../autoloader.php';
    
    $pos = $_GET['pos'] ?? null;
    $rawAnswers = $_POST['answerOrder'] ?? null;
    $idQuiz = $_GET['idQuiz'] ?? null;
    $insert = false;

    $questionsAnswersCont = new QuestionsAnswers();
    $question = $questionsAnswersCont->getQuestionAnswersByPositionn($pos, $idQuiz);
    $correct = ($rawAnswers == $question[0]['Json']) ? 1 : 0;
    $IdQuestion = $questionsAnswersCont->getQuestionIdByPositionn($pos);

    $anonymCont = new anonymous();
    $userId = $anonymCont->getUserIdd($_SESSION['anonymous'])['Id'];

    $questionsAnswersCont->insertUserAnswerr('', $rawAnswers, $correct, $userId, $idQuiz, $IdQuestion);

    $minMax = $questionsAnswersCont->getQuestionMinMaxPositionByQuizId();
    $maxPos = (int)$minMax['maxPos'];
    $nextPos = ((int)$pos) + 1;

    if ($maxPos >= $nextPos){
        header("location: ../pages/takeQuiz.page.php?idQuiz=$idQuiz&pos=$nextPos");
    }
    else {
        header("location: ../pages/userquizoverview.php");
    }
}
else{
    $_SESSION['error'] = 'Something went wrong during form submit.';
    header("location: ../pages/home.page.php");
}
