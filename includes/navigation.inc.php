<?php
$questionsAnswersCont = new QuestionsAnswers();
$currentPosition = $questionId ? $questionsAnswersCont->getQuestionPositionById($questionId) : null;
if (isset($_POST['prev'])){
    if($currentPosition > 1){
        $prevQuestion = $questionsAnswersCont->getQuestionByPositionn($currentPosition - 1);
        $prevId = $prevQuestion['Id'];
        $prevType = $prevQuestion['IdAnswerType'];
        header("location: ../pages/createQuiz.page.php?id=$prevId&type=$prevType");
        exit;
    }
    else{
        header("location: ../pages/createQuiz.page.php?type=quizName");
        exit;
    }
}

if (isset($_POST['next'])){
    $lastPosition = (int)$questionsAnswersCont->getQuestionMinMaxPositionByQuizId()['maxPos'];
    if ($insert === false && $currentPosition == $lastPosition){
        header("location: ../pages/createQuiz.page.php?type=pick");
        exit;
    }
    else if ($insert === true){
        header("location: ../pages/createQuiz.page.php?type=pick");
        exit;
    }
    else{
        $nextQuestion = $questionsAnswersCont->getQuestionByPositionn($currentPosition + 1);
        $nextId = $nextQuestion['Id'];
        $nextType = $nextQuestion['IdAnswerType'];
        header("location: ../pages/createQuiz.page.php?id=$nextId&type=$nextType");
        exit;
    }
}

if (isset($_POST['overview'])){
    
    header("location: ../pages/quizes.page.php");
    exit;

}