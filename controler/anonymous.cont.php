<?php
class anonymous extends User {

    function newAnonymous($uid){
        $this->createAnonymous($uid);
    }

    function getUserIdd($uid){
        return $this->getUserId($uid);
    }
}