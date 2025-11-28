<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
if (isset($_SESSION['error'])){
    unset($_SESSION['error']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['type'] == 'back') {

    include '../autoloader.php';
    if (isset($_SESSION['error'])) {
        unset($_SESSION['error']);
    }

    $questionsAnswersCont = new QuestionsAnswers();
    $quizId = $_SESSION['quizId'];
    $totalCount = (int)$questionsAnswersCont->getQuizQuestionCountt($quizId);
    $lastQuestionId = $questionsAnswersCont->getQuestionIdByPositionn($totalCount);
    $lastQuestionType = $questionsAnswersCont->getQuestionTypeById($lastQuestionId)['IdAnswerType'];

    if ($lastQuestionId == null) {
        header("location: ../pages/createQuiz.page.php?type=quizName");
        exit;
    }

    header("location: ../pages/createQuiz.page.php?id=$lastQuestionId&type=$lastQuestionType");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['mode'] !== 'take') {

    include '../autoloader.php';
    if (isset($_SESSION['error'])) {
        unset($_SESSION['error']);
    }

    $questionText = $_POST['question_text'] ?? '';
    $questionId = $_POST['questionId'] ?? null;
    $leftRaw = $_POST['left']  ?? [];
    $rightRaw = $_POST['right'] ?? [];

    $pairs = [];
    for ($i = 0; $i < count($leftRaw); $i++) {
        $l = trim($leftRaw[$i] ?? '');
        $r = trim($rightRaw[$i] ?? '');
        if ($l !== '' && $r !== '') {
            $pairs[] = ['left' => $l, 'right' => $r];
        }
    }

    if (count($pairs) < 2) {
        $_SESSION['error'] = "You must enter at least 2 matching pairs.";
        header("location: ../pages/createQuiz.page.php?id=$questionId&type=4");
        exit;
    }

    $matchAnswersCont = new matchAnswers();
    $insert = false;

    if (!empty($questionId)) {
        $matchAnswersCont->update($questionId, $questionText, $pairs);
    }
    else {
        $matchAnswersCont->insert($questionText, $pairs);
        $insert = true;
    }

    include_once 'navigation.inc.php';
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['mode'] == 'take') {

    include '../autoloader.php';

    $pos = $_GET['pos'] ?? null;
    $idQuiz = $_GET['idQuiz'] ?? null;
    $rawUser = $_POST['matches'] ?? null;
    $insert = false;

    if (!$rawUser) {
        $_SESSION['error'] = "No answer submitted.";
        header("location: ../pages/takeQuiz.page.php?idQuiz=$idQuiz&pos=$pos");
        exit;
    }

    $userMatches = json_decode($rawUser, true);

    $questionsAnswersCont = new QuestionsAnswers();
    $question = $questionsAnswersCont->getQuestionAnswersByPositionn($pos, $idQuiz);

    $correctJson = $question[0]['Json'];
    $correctPairs = json_decode($correctJson, true);

    $correct = 0;
    $allCorrect = true;
    foreach ($userMatches as $match) {
        $leftText  = $match['left'] ?? null;
        $rightText = $match['right'] ?? null;

        if ($leftText === null || $rightText === null) {
            $allCorrect = false;
            break;
        }

        $found = false;
        foreach ($correctPairs as $pair) {
            if ($pair['left'] === $leftText && $pair['right'] === $rightText) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $allCorrect = false;
            break;
        }
    }
    if ($allCorrect) {
        $correct = 1;
    }

    $IdQuestion = $questionsAnswersCont->getQuestionIdByPositionn($pos);

    $anonymCont = new anonymous();
    $userId = $anonymCont->getUserIdd($_SESSION['anonymous'])['Id'];

    $questionsAnswersCont->insertUserAnswerr('', $rawUser, $correct, $userId, $idQuiz, $IdQuestion);

    $minMax = $questionsAnswersCont->getQuestionMinMaxPositionByQuizId();
    $maxPos = (int)$minMax['maxPos'];
    $nextPos = ((int)$pos) + 1;

    if ($maxPos >= $nextPos) {
        header("location: ../pages/takeQuiz.page.php?idQuiz=$idQuiz&pos=$nextPos");
    } else {
        header("location: ../pages/userquizoverview.php");
    }
    exit;
}

$_SESSION['error'] = 'Something went wrong during matching question submit.';
header("location: ../pages/home.page.php");
exit;
