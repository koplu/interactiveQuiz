<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
class Questions extends Database {

    # insert
    protected function createQuestion($text, $answerType){
        $conn = $this->connect();

        try {
            $position = $this->getLastQuestionPosition();
            $position = $position + 1;

            $stmt = $conn->prepare("insert into tbquestions(Text,IdQuiz,IdAnswerType,Position) values(:text,:IdQuiz,:IdAnswerType,:Position)");
            $stmt->bindParam(':text', $text);
            $stmt->bindParam(':IdQuiz', $_SESSION["quizId"]);
            $stmt->bindParam(':IdAnswerType', $answerType);
            $stmt->bindParam(':Position', $position);
            $stmt->execute();
            
            $lastQuestionId = $conn->lastInsertId();

            return $lastQuestionId;
        }
        catch (PDOException $e) {
            echo "createQuestion failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function createImageOptions($correct, $idQuestion, $imagePath){
        $conn = $this->connect();

        try {
            $stmt = $conn->prepare("insert into tbanswers(Correct,IdQuestion,imagePath) 
            values(:correct,:IdQuestion,:imagePath)");
            $stmt->bindParam(':correct', $correct);
            $stmt->bindParam(':IdQuestion', $idQuestion);
            $stmt->bindParam(':imagePath', $imagePath);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "createImageOptions failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function createOrderAnswer($json, $idQuestion){
        $conn = $this->connect();

        try {
            $stmt = $conn->prepare("insert into tbanswers(Json,IdQuestion) 
            values(:Json,:IdQuestion)");
            $stmt->bindParam(':Json', $json);
            $stmt->bindParam(':IdQuestion', $idQuestion);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "createOrderAnswer failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function createMatchAnswers($json, $idQuestion){
        $conn = $this->connect();

        try {
            $stmt = $conn->prepare("insert into tbanswers(Json,IdQuestion) 
            values(:Json,:IdQuestion)");
            $stmt->bindParam(':Json', $json);
            $stmt->bindParam(':IdQuestion', $idQuestion);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "createMatchAnswers failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function insertUserAnswer($imagePath, $orderAnswer, $correct, $IdUser, $IdQuiz, $IdQuestion){
        $conn = $this->connect();

        try {
            $stmt = $conn->prepare("insert into tbuseranswers(imagePath,orderAnswer,correct) 
            values(:imagePath,:orderAnswer,:correct)");
            $stmt->bindParam(':imagePath', $imagePath);
            $stmt->bindParam(':orderAnswer', $orderAnswer);
            $stmt->bindParam(':correct', $correct);
            $stmt->execute();

            $lastInsertId = $conn->lastInsertId();

            $stmt = $conn->prepare("insert into tbuserquizprogress(IdUser,IdQuiz,IdQuestion,IdAnswer) 
            values(:IdUser,:IdQuiz,:IdQuestion,:IdAnswer)");
            $stmt->bindParam(':IdUser', $IdUser);
            $stmt->bindParam(':IdQuiz', $IdQuiz);
            $stmt->bindParam(':IdQuestion', $IdQuestion);
            $stmt->bindParam(':IdAnswer', $lastInsertId);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "insertUserAnswer failed: " . $e->getMessage();
        }

        $conn = null;
    }

    //get
    protected function getQuestions(){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select q.*, t.Type from tbquestions q
            join tbanswertype t on q.IdAnswerType = t.Id where q.IdQuiz = :IdQuiz
            order by q.Position");
            $stmt->bindParam(':IdQuiz', $_SESSION['quizId']);

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        }
        catch (PDOException $e) {
            echo "getQuestions failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getQuestionsForTest($id){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select q.Id, q.Text, q.IdQuiz, q.IdAnswerType, q.Position,
            t.Text, t.Boolean, t.Json, t.Correct, t.IdQuestion, t.imagePath from tbquestions q
            join tbanswers t on q.Id = t.IdQuestion where q.IdQuiz = :IdQuiz order by q.Position");
            $stmt->bindParam(':IdQuiz', $id);

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        }
        catch (PDOException $e) {
            echo "getQuestionsForTest failed: " . $e->getMessage();
        }

        $conn = null;
    }
    
    protected function getQuiz(){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select * from tbquiz where Id = :IdQuiz");
            $stmt->bindParam(':IdQuiz', $_SESSION['quizId']);

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetch();
        }
        catch (PDOException $e) {
            echo "getQuiz failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getQuizByUrlId($id){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select * from tbquiz where Id = :IdQuiz");
            $stmt->bindParam(':IdQuiz', $id);

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetch();
        }
        catch (PDOException $e) {
            echo "getQuizByUrlId failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getQuestion($id){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select q.Id, q.Text as qText, q.IdAnswerType,
            a.Text as aText, a.Json, a.Correct, a.imagePath
            from tbquestions q
            join tbanswers a
            on q.Id = a.IdQuestion
            where a.IdQuestion = :id");
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        }
        catch (PDOException $e) {
            echo "getQuestion failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getPictureChoiceImages($id){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select imagePath from tbanswers
            where IdQuestion = :id");
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        }
        catch (PDOException $e) {
            echo "getPictureChoiceImages failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getLastQuestionPosition(){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select COALESCE(MAX(Position), 0) as maxPos from tbquestions where IdQuiz = :IdQuiz");
            $stmt->bindParam(':IdQuiz', $_SESSION['quizId']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['maxPos'];
        }
        catch (PDOException $e) {
            echo "getLastQuestionPosition failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getQuestionPosition($questionId){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select Position from tbquestions where Id = :Id");
            $stmt->bindParam(':Id', $questionId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['Position'];
        }
        catch (PDOException $e) {
            echo "getQuestionPosition failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getQuestionMinMaxPosition(){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select MIN(Position) as minPos, MAX(Position) as maxPos
                from tbquestions where IdQuiz = :IdQuiz");
            $stmt->bindParam(':IdQuiz', $_SESSION['quizId']);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo "getQuestionMinMaxPosition failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getQuestionType($idQuestion){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select IdAnswerType from tbquestions where Id = :Id");
            $stmt->bindParam(':Id', $idQuestion);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo "getQuestionType failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getQuestionIdByPosition($Position){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select Id from tbquestions where Position = :Position and IdQuiz = :idquiz");
            $stmt->bindParam(':Position', $Position);
            $stmt->bindParam(':idquiz', $_SESSION['quizId']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['Id'];
        }
        catch (PDOException $e) {
            echo "getQuestionIdByPosition failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getQuestionByPosition($Position){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select Id, IdAnswerType from tbquestions
                where Position = :Position and IdQuiz = :idquiz");
            $stmt->bindParam(':Position', $Position);
            $stmt->bindParam(':idquiz', $_SESSION['quizId']);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo "getQuestionByPosition failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getQuestionAnswersByPosition($pos, $idQuiz){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select q.Id, q.Text as qText, q.IdAnswerType,
            a.Text as aText, a.Json, a.Correct, a.imagePath
            from tbquestions q
            join tbanswers a
            on q.Id = a.IdQuestion
            where q.Position = :pos
            and q.IdQuiz = :idQuiz");
            $stmt->bindParam(':pos', $pos);
            $stmt->bindParam(':idQuiz', $idQuiz);

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        }
        catch (PDOException $e) {
            echo "getQuestionAnswersByPosition failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getUserAnswer($IdUser, $IdQuiz, $IdQuestion){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select IdAnswer from tbuserquizprogress where
            IdUser = :IdUser and
            IdQuiz = :IdQuiz and
            IdQuestion = :IdQuestion");
            $stmt->bindParam(':IdUser', $IdUser);
            $stmt->bindParam(':IdQuiz', $IdQuiz);
            $stmt->bindParam(':IdQuestion', $IdQuestion);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetch();
        }
        catch (PDOException $e) {
            echo "getUserAnswer failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getAllUserAnswers($IdUser, $IdQuiz){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select q.Id as questionId, q.Text as questionText,
                uqa.IdAnswer, ua.correct as isCorrect
                from tbuserquizprogress uqa
                join tbquestions q on q.Id = uqa.IdQuestion
                join tbuseranswers ua on ua.Id = uqa.IdAnswer
                where uqa.IdUser = :userId
                and uqa.IdQuiz = :quizId
                order by q.Id");
            $stmt->bindParam(':userId', $IdUser);
            $stmt->bindParam(':quizId', $IdQuiz);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        }
        catch (PDOException $e) {
            echo "getAllUserAnswers failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getAllQuizResults($idQuiz){
        if ($idQuiz == null){
            $idQuiz = $_SESSION['quizId'];
        }
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select u.Username, p.IdUser, sum(a.correct) as score
                from tbuserquizprogress p
                join tbuseranswers a
                on p.IdAnswer = a.Id
                join tbusers u
                on p.IdUser = u.Id
                where p.IdQuiz = :quizId
                group by p.IdUser
                order by score desc;");
            $stmt->bindParam(':quizId', $idQuiz);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        }
        catch (PDOException $e) {
            echo "getAllQuizResults failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getQuizQuestionCount($idQuiz){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select count(*) as countTotal from tbquestions
                where IdQuiz = :quizId");
            $stmt->bindParam(':quizId', $idQuiz);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchColumn();
        }
        catch (PDOException $e) {
            echo "getQuizQuestionCount failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function hasUserCompletedQuiz($idUser, $idQuiz){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select count(*) as attempt 
                from tbuserquizprogress 
                where IdUser = :IdUser and IdQuiz = :IdQuiz");
            $stmt->bindParam(':IdUser', $idUser);
            $stmt->bindParam(':IdQuiz', $idQuiz);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchColumn();
        }
        catch (PDOException $e) {
            echo "hasUserCompletedQuiz failed: " . $e->getMessage();
        }

        $conn = null;
    }

    # updates
    protected function updateQuestion($id, $text){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("update tbquestions set Text = :text where Id = :Id");
            $stmt->bindParam(':text', $text);
            $stmt->bindParam(':Id', $id);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "updateQuestion failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function updateOrderAnswer($id, $json){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("update tbanswers set Json = :Json where IdQuestion = :Id");
            $stmt->bindParam(':Json', $json);
            $stmt->bindParam(':Id', $id);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "updateOrderAnswer failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function updateCorrectImage($id, $imagePath){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("update tbanswers set Correct = 0 where IdQuestion = :Id");
            $stmt->bindParam(':Id', $id);
            $stmt->execute();

            $stmt = $conn->prepare("update tbanswers set Correct = 1 where imagePath = :path");
            $stmt->bindParam(':path', $imagePath);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "updateCorrectImage failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function updateMatchAnswers($id, $json){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("update tbanswers set Json = :Json where IdQuestion = :Id");
            $stmt->bindParam(':Json', $json);
            $stmt->bindParam(':Id', $id);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "updateMatchAnswers failed: " . $e->getMessage();
        }

        $conn = null;
    }

    # deletes
    protected function deletePictureChoice($id){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("delete from tbanswers where IdQuestion = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "deletePictureChoice failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function deleteQuestion($id){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select IdQuiz, IdAnswerType, Position from tbquestions where Id = :IdQuestion");
            $stmt->bindParam(':IdQuestion', $id);
            $stmt->execute();
            $question = $stmt->fetch(PDO::FETCH_ASSOC);

            $quizId = (int)$question['IdQuiz'];
            $IdAnswerType = (int)$question['IdAnswerType'];
            $position = (int)$question['Position'];

            if ($IdAnswerType == 4){
                $removeImages = $this->getPictureChoiceImages($id);

                foreach ($removeImages as $img) {
                    if (file_exists($img['imagePath'])) {
                        unlink($img['imagePath']);
                    }
                }
            }

            $stmt = $conn->prepare("delete from tbanswers where IdQuestion = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt = $conn->prepare("delete from tbquestions where Id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt = $conn->prepare("update tbquestions set Position = Position - 1
                where IdQuiz = :quizId
                and Position > :deletedPosition");
            $stmt->bindParam(':quizId', $quizId);
            $stmt->bindParam(':deletedPosition', $position);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "deleteQuestion failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function deleteQuizProgress($quizId){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("delete ua
                from tbuseranswers ua
                join tbuserquizprogress uqp
                on ua.Id = uqp.IdAnswer
                where uqp.IdQuiz = :idQuiz;");
            $stmt->bindParam(':idQuiz', $quizId);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "deleteQuizProgress failed: " . $e->getMessage();
        }

        $conn = null;
    }
}
