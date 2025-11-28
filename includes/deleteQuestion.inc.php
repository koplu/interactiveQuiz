<?php

include '../autoloader.php';

$questionAnswerControler = new QuestionsAnswers();
$questionAnswerControler->deleteQuestionById($_GET['id']);

header("location: ../pages/quizOverview.page.php");
