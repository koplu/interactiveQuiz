<?php
if (isset($_POST['next'])){

    $quizName = $_POST['question'];
    $quizId = $_POST['quizId'] ?? null;

    include '../autoloader.php';
    $quizNameCont = new createQuizName($quizName);

    //execute function
    if (!empty($quizId)){
        $quizNameCont->updateName();
    }
    else {
        $quizNameCont->createName();
    }

    $questionsAnswersCont = new QuestionsAnswers();
    $nextQuestion = $questionsAnswersCont->getQuestionByPositionn(1);
    if ($nextQuestion == null){
        header("location: ../pages/createQuiz.page.php?type=pick");
        exit;
    }
    $nextId = $nextQuestion['Id'];
    $nextType = $nextQuestion['IdAnswerType'];
    header("location: ../pages/createQuiz.page.php?id=$nextId&type=$nextType");
    exit;
}
elseif (isset($_GET['type']) && $_GET['type'] == 'quizName'){

    if (isset($_GET['act']) && $_GET['act'] == 'new'){
        $quizName = '';
        $quizId = '';
        return;
    }

    include_once '../autoloader.php';
    $quizNameCont = new createQuizName('quizName');
    $quiz = $quizNameCont->getQuizWhole() ?? '';
    $quizName = $quiz['Name'] ?? '';
    $quizId = $quiz['Id'] ?? '';
}
elseif ($_POST['overview']){
    header("location: ../pages/quizes.page.php");
    exit;
}