<?php
include_once '../autoloader.php';

if (isset($_GET['idQuiz'])){
    $questionsAnswersControler = new QuestionsAnswers();
    $idQuiz = $_GET['idQuiz'];

    $quizName = $questionsAnswersControler->getQuizFromId($idQuiz)['Name'];
    $quizResults = $questionsAnswersControler->getAllQuizResultss($idQuiz);
    $total = $questionsAnswersControler->getQuizQuestionCountt($idQuiz);

    foreach ($quizResults as &$user) {
        $user['answers'] = $questionsAnswersControler->getAllUserAnswerss(
            $user['IdUser'],
            $idQuiz
        );
    }
    unset($user);

}
