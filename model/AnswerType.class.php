<?php
class AnswerType extends Database {

    protected function getTypes(){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select type from tbanswertype");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        }
        catch (PDOException $e) {
            echo "getTypes failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getType($id){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select * from tbtypeofanswers where id = :id");
            $stmt->bindParam("id", $id);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        }
        catch (PDOException $e) {
            echo "getTypes failed: " . $e->getMessage();
        }

        $conn = null;
    }
}