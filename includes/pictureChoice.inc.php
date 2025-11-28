<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
if (isset($_POST['question_text']) && isset($_FILES['images'])) {
    include '../autoloader.php';
    if (isset($_SESSION['error'])){
        unset($_SESSION['error']);
    }
    
    $question = $_POST['question_text'];
    $questionId = $_POST['questionId'] ?? null;
    $correct = $_POST['correct_answer'];
    $files = $_FILES['images'];
    $insert = false;

    $questionAnswerControler = new QuestionsAnswers();

    if (!empty($questionId)){
        $questionAnswerControler->updatePictureChoice($questionId,$question,$correct, $files);
    }
    else {
        $questionAnswerControler->insert($question, $correct, $files);
        $insert = true;
    }

    include_once 'navigation.inc.php';
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['mode'] == 'take'){
    include '../autoloader.php';
    
    $pos = $_GET['pos'] ?? null;
    $idQuiz = $_GET['idQuiz'] ?? null;
    $correct = $_POST['correct_answer'];
    
    $questionsAnswersCont = new QuestionsAnswers();
    $question = $questionsAnswersCont->getQuestionAnswersByPositionn($pos, $idQuiz);
    $correctIndex = null;
    foreach ($question as $index => $row) {
        if ($row['Correct'] == "1") {
            $correctIndex = $index;
            break;
        }
    }
    $correctStatus = ($correct == $correctIndex) ? 1 : 0;
    $IdQuestion = $questionsAnswersCont->getQuestionIdByPositionn($pos);

    $anonymCont = new anonymous();
    $userId = $anonymCont->getUserIdd($_SESSION['anonymous'])['Id'];

    $questionsAnswersCont->insertUserAnswerr('', '[]', $correctStatus, $userId, $idQuiz, $IdQuestion);
 
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
    header("location: ../pages/createQuiz.page.php?type=pick");
}
