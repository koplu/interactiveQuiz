<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
class User extends Database {

    protected function getUserId($uid){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select Id from tbusers where Username = :uid");
            $stmt->bindParam(':uid', $uid);

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetch();
        }
        catch (PDOException $e) {
            echo "getUserId failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function createUser($name,$surname,$uid, $pwd){

        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("insert into tbusers(Name,Surname,Username,Password) values(:name,:surname,:uid,:pwd)");

            $hash = password_hash($pwd, PASSWORD_DEFAULT);

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':uid', $uid);
            $stmt->bindParam(':pwd', $hash);

            $stmt->execute();
        }
        catch (PDOException $e) {
            echo "createUser failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function checkUser($uid){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select Username from tbusers where Username = :uid");
            $stmt->bindParam(':uid', $uid);

            if (!$stmt->execute()){
                $stmt = null;
                header("location: ../index.php?error=stmtfailed");
                exit();
            }

            if ($stmt->rowCount() > 0){
                $resultCheck = false;
            }
            else {
                $resultCheck = true;
            }

            return $resultCheck;
        }
        catch (PDOException $e) {
            echo "checkUser failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function getUser($uid, $pwd){
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select Password from tbusers where Username = :uid");
            $stmt->bindParam(':uid', $uid);

            if (!$stmt->execute()){
                $stmt = null;
                header("location: ../index.php?error=db error");
                exit();
            }

            if ($stmt->rowCount() == 0){
                $stmt = null;
                header("location: ../index.php?error=wrongLogin");
                exit();
            }

            $pwdHash = $stmt->fetch(PDO::FETCH_ASSOC);
            $checkPwd = password_verify($pwd, $pwdHash['Password']);

            if ($checkPwd == false){
                $stmt = null;
                header("location: ../index.php?error=wrongLogin");
                exit();
            }
            elseif($checkPwd == true){
                return true;
            }
        }
        catch (PDOException $e) {
            echo "checkUser failed: " . $e->getMessage();
        }

        $conn = null;
    }

    protected function createAnonymous($uid){

        $conn = $this->connect();
        try {
            $stmt = $conn->prepare("select Username from tbusers where Username = :uid");
            $stmt->bindParam(':uid', $uid);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();
            if ($result['Username'] == $uid){
                $_SESSION['anonymous'] = $uid;
                return;
            }
            else{
                $stmt = $conn->prepare("insert into tbusers(Username) values(:uid)");
                $stmt->bindParam(':uid', $uid);
                $stmt->execute();

                $_SESSION['anonymous'] = $uid;
            }
        }
        catch (PDOException $e) {
            echo "createAnonymous failed: " . $e->getMessage();
        }

        $conn = null;
    }
}
