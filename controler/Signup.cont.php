<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
class Signup extends User {

    private $name;
    private $surname;
    private $uid;
    private $pwd;

    public function __construct($name,$surname,$uid,$pwd){
        $this->name = $name;
        $this->surname = $surname;
        $this->uid = $uid;
        $this->pwd = $pwd;
    }

    function signUpUser(){
        if ($this->uidTakenCheck() == false){
            header("location: ../pages/signup.page.php?error=uidtaken");
            exit();
        }

        $this->createUser($this->name,$this->surname,$this->uid, $this->pwd);
        $_SESSION["user"] = $this->uid;
        header("location: ../pages/home.page.php");
    }

    private function uidTakenCheck(){
        if (!$this->checkUser($this->uid)){
            $result = false;
        }
        else {
            $result = true;
        }
        return $result;
    }
}