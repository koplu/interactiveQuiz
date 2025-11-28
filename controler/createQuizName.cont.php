<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
class createQuizName extends QuizName {

    private $quizName;

    public function __construct($quizname){
        $this->quizName = $quizname;
    }

    function createName(){
        $this->createQuizName($this->quizName, $_SESSION["user"]);
    }

    function updateName(){
        $this->updateQuizName($this->quizName);
    }

    function getQuizWhole(){
        return $this->getQuiz();
    }

    public function getAllUserQuizes(){
        return $this->getAllQuizes();
    }

    public function deleteQuizz($id){
        return $this->deleteQuiz($id);
    }
}