<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
class QuizName extends Database {

    protected function createQuizName($name, $uid){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select Id from tbusers where Username = :uid");
            $stmt->bindParam(':uid', $uid);
            $stmt->execute();
            $id = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $stmt = $conn->prepare("insert into tbquiz(Name,Date,IdUser) values(:name,:date,:IdUser)");
            $stmt->bindParam(':name', $name);
            $currentDateTime = date("Y-m-d H:i:s"); 
            $stmt->bindParam(':date', $currentDateTime);
            $stmt->bindParam(':IdUser', $id["Id"]);
            $stmt->execute();
            
            $lastId = $conn->lastInsertId();
            $_SESSION["quizId"] = $lastId;
        }
        catch (PDOException $e) {
            echo "createQuizName failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function updateQuizName($name){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("update tbquiz set Name = :name where Id = :Id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':Id', $_SESSION["quizId"]);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "updateQuizName failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getUserId(){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select Id from tbusers where Username = :uid");
            $stmt->bindParam(':uid', $_SESSION['user']);

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetch();
        }
        catch (PDOException $e) {
            echo "getUserId failed: " . $e->getMessage();
        }

        $conn = null;
    }
    
    protected function getQuiz(){
        $userId = $this->getUserId()['Id'];

        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select * from tbquiz where Id = :id and IdUser = :idUser");
            $stmt->bindParam(':id', $_SESSION["quizId"]);
            $stmt->bindParam(':idUser', $userId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo "getQuiz failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getAllQuizes(){
        $userId = $this->getUserId()['Id'];

        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select * from tbquiz where IdUser = :idUser");
            $stmt->bindParam(':idUser', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo "getAllQuizes failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function deleteQuiz($id){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("delete from tbquiz where Id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "deleteQuiz failed: " . $e->getMessage();
        }

        $conn = null;
    }
}
