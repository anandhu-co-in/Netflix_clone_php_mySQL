<?php

class User{

    private $con, $sqlData;


    public function __construct($con,$username){
        $this->con = $con;
        $querry=$con->prepare("SELECT * from users WHERE username=:username");
        $querry->bindValue(":username",$username);
        $querry->execute();
        $this->sqlData=$querry->fetch(PDO::FETCH_ASSOC);
    }

    public function getFirstName(){
        return $this->sqlData["firstName"];

    }

    public function getLastName(){
        return $this->sqlData["lastName"];
    }

    public function getEmail(){
        return $this->sqlData["email"];
    }

    public function getIsSubscribed(){
        return $this->sqlData["isSubscribed"];
    }

    public function getUserName(){
        return $this->sqlData["userName"];
    }


    //in users table set subscription as 1, to prevent unsubscribed users from playing videos
    public function setIsSubscribed($value){

        $querry=$this->con->prepare("UPDATE users SET isSubscribed=:isSubscribed WHERE username=:username");
        $querry->bindValue(":isSubscribed",$value);
        $querry->bindValue(":username",$this->getUserName());

        if($querry->execute()){
            $this->sqlData["isSubscribed"]=$value;
            return true;
        };

        return false;

    }


}

?>