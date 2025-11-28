<?php 
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
class orderAnswer extends Questions {

    function insert($question, $answers, $orders){
        if (empty($question)) {
            $_SESSION['error'] = 'Some form fields are empty.';
            return;
        }

        $filled = 0;
        foreach ($answers as $i => $a) {
            if ($a !== '') $filled++;
            if ($a === '' && $orders[$i] !== "") {
                $_SESSION['error'] = "Answer text missing for an order.";
                return;
            }
            if ($orders[$i] !== "" && ($orders[$i] < 1 || $orders[$i] > 4)) {
                $_SESSION['error'] = "Order must be between 1 and 4.";
                return;
            }
        }
        if ($filled < 2) {
            $_SESSION['error'] = "Please provide at least 2 answers.";
            return;
        }

        $idQuestion = $this->createQuestion($question, 5);
        $finalAnswer = [];

        foreach ($answers as $key => $value) {
            $finalAnswer[] = [
                "text" => trim($value),
                "order" => intval($orders[$key])
            ];
        }

        $jsonAnswers = json_encode($finalAnswer);
        $this->createOrderAnswer($jsonAnswers, $idQuestion);
    }

    function updateQuestionText($id, $question, $answers, $orders){
        if (empty($question)) {
            $_SESSION['error'] = 'Some form fields are empty.';
            return;
        }

        $filled = 0;
        foreach ($answers as $i => $a) {
            if ($a !== '') $filled++;
            if ($a === '' && $orders[$i] !== "") {
                $_SESSION['error'] = "Answer text missing for an order.";
                return;
            }
            if ($orders[$i] !== "" && ($orders[$i] < 1 || $orders[$i] > 4)) {
                $_SESSION['error'] = "Order must be between 1 and 4.";
                return;
            }
        }
        if ($filled < 2) {
            $_SESSION['error'] = "Please provide at least 2 answers.";
            return;
        }

        $this->updateQuestion($id, $question);
        $finalAnswer = [];

        foreach ($answers as $key => $value) {
            $finalAnswer[] = [
                "text" => trim($value),
                "order" => intval($orders[$key])
            ];
        }

        $jsonAnswers = json_encode($finalAnswer);
        $this->updateOrderAnswer($id, $jsonAnswers);
    }
}