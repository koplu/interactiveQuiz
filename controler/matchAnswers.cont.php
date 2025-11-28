<?php 
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
class matchAnswers extends Questions {

    function insert($question, $pairs) {

        if (empty($question) || empty($pairs)) {
            $_SESSION['error'] = 'Some form fields are empty.';
            return;
        }

        $idQuestion = $this->createQuestion($question, 9);
        $jsonData = json_encode($pairs, JSON_UNESCAPED_UNICODE);
        $this->createMatchAnswers($jsonData, $idQuestion);
    }

    public function update($questionId, $questionText, $pairs) {
        if (empty($questionText) || empty($pairs)) {
            $_SESSION['error'] = 'Question text or matching pairs cannot be empty.';
            return;
        }

        $jsonData = json_encode($pairs, JSON_UNESCAPED_UNICODE);
        $this->updateQuestion($questionId, $questionText);
        $this->updateMatchAnswers($questionId, $jsonData);
    }
}