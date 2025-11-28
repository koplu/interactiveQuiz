<?php
class Login extends User {

    private $uid;
    private $pwd;

    public function __construct($uid,$pwd){
        $this->uid = $uid;
        $this->pwd = $pwd;

    }

    function loginUser(){

        if($this->getUser($this->uid, $this->pwd) == true){
            session_start();
            $_SESSION["user"] = $this->uid;
        }
    }
}