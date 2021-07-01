<?php

class Account {

    private $con;
    private $errorArray= array();

    public function __construct($con){
        $this->con=$con;
    }


    //Validate new details and if no errors are found, update the users table
    public function updateDetails($fn,$ln,$em,$un){
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateNewEmail($em,$un);

        if(empty($this->errorArray)){
            
            $querry=$this->con->prepare("UPDATE users SET firstName=:fn, lastName=:ln, email=:em WHERE userName=:un");
            $querry->bindValue(":fn", $fn);
            $querry->bindValue(":ln", $ln);
            $querry->bindValue(":em", $em);
            $querry->bindValue(":un", $un);

            return $querry->execute(); //execute() returns true/false based on success or fail

        }
        return false;
    }


    //Validate the inputs, if no errors are found, inserthem them to db
    public function register($firstName,$lastName,$userName,$email,$email2,$password,$password2){
        $this->validateFirstName($firstName);
        $this->validateLastName($lastName);
        $this->validateUserName($userName);
        $this->validateEmail($email,$email2);
        $this->validatePasswords($password,$password2);

        if(empty($this->errorArray)){
            return $this->insertUserDetails($firstName,$lastName,$userName,$email,$password);
        }

        return false;
    }


    //Insert thene user details into users table
    public function insertUserDetails($firstName,$lastName,$userName,$email,$password){

        //Add password hashing here 32:2:54

        $querry=$this->con->prepare("INSERT INTO users (firstName, lastName, userName, email, password)VALUES (:fn, :ln, :un, :em, :pw)");
        $querry->bindValue(":fn", $firstName);
        $querry->bindValue(":ln", $lastName);
        $querry->bindValue(":un", $userName);
        $querry->bindValue(":em", $email);
        $querry->bindValue(":pw", $password);

        return $querry->execute(); //execute() returns true/false based on success or fail

        //For debugging(havent tried these)
        //$querry->execute();
        //var_dump($querry->errorInfo());
    }    


    //Validate firstname is of proper length
    private function validateFirstName($fn){
        if(strlen($fn)<2||strlen($fn)>25){
            array_push($this->errorArray,Constants::$firstNameCharectors);
        }
    }


    // Validate lastname is of proper length
    private function validateLastName($ln){
        if(strlen($ln)<2||strlen($ln)>25){
            array_push($this->errorArray,Constants::$lastNameCharectors);
        }
    }


    //Validte username has properlength and also doesnt exists alreay in users table
    private function validateUserName($un){
        
        if(strlen($un)<2||strlen($un)>25){
            array_push($this->errorArray,Constants::$userNameCharectors);
            return;
        }

        $querry = $this->con->prepare("SELECT * FROM users WHERE userName=:un");
        $querry->bindValue(":un", $un);
        $querry->execute();
       
        if($querry->rowCount() != 0){
             array_push($this->errorArray,Constants::$userNameAlreadyExits);
        }

    }
   

    //Validate that email match, and is in proper format, and also it doesnt already exist
    public function validateEmail($email,$email2){

        if($email!=$email2){
            array_push($this->errorArray,Constants::$emailsDoesntMatch);
            return;
        }
        
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray,Constants::$invalidEmail);
            return;
        }

        $querry=$this->con->prepare("SELECT * FROM users WHERE email=:email");
        $querry->bindValue(":email",$email);
        $querry->execute();

        if($querry->rowCount() !=0){
            array_push($this->errorArray,Constants::$emailAlreadyExit);
        }

    }


    //Validate email, separate function to use while user updates details. Update should work if user uses his own email(which exists in the db)
    public function validateNewEmail($email,$username){
        
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray,Constants::$invalidEmail);
            return;
        }

        $querry=$this->con->prepare("SELECT * FROM users WHERE email=:email AND username !=:un");
        $querry->bindValue(":email",$email);
        $querry->bindValue(":un",$username);
        $querry->execute();

        if($querry->rowCount() !=0){
            array_push($this->errorArray,Constants::$emailAlreadyExit);
        }

    }


    //Validate passwords match
    public function validatePasswords($pw1,$pw2){

        if($pw1!=$pw2){
            array_push($this->errorArray,Constants::$passwordDoesntMatch);
            return;
        }
        
    }

    
    //Login function to check then entered username and password is valid
    public function login($userName,$password){

        $querry=$this->con->prepare("SELECT * FROM users WHERE userName=:un AND password=:pw");
        $querry->bindValue(":un",$userName);
        $querry->bindValue(":pw",$password);

        $querry->execute();

        if($querry->rowCount()==1){
            return true;          
        }

        array_push($this->errorArray,Constants::$loginFailed);
        return false;

    }


    //If a specific error message is present in the error array just return it
    public function getError($error){
        if(in_array($error,$this->errorArray)){
            return "<span class='formErrorMessage'>$error</span>";
        }
    }


    //Get the first error form error array if present
    public function getFirstError(){
        if(!empty($this->errorArray)){
            return $this->errorArray[0];
        }
    }


    //Update new password to db
    public function updatePassword($oldPw,$pw,$pw2,$un){

        //Apply hashing
        $this->validateOldPassword($oldPw,$un);
        $this->validatePasswords($pw,$pw2);

        if(empty($this->errorArray)){
            
            $querry=$this->con->prepare("UPDATE users SET password=:pw WHERE userName=:un");
            $querry->bindValue(":pw", $pw);
            $querry->bindValue(":un", $un);

            return $querry->execute(); 

        }
        return false;
    }    


    //Validate old password is correct, used during password update
    public function validateOldPassword($password,$userName){
        
        $querry=$this->con->prepare("SELECT * FROM users WHERE userName=:un AND password=:pw");
        $querry->bindValue(":un",$userName);
        $querry->bindValue(":pw",$password);

        $querry->execute();

        if($querry->rowCount()==1){
            return true;          
        }

        array_push($this->errorArray,Constants::$wrongOldPassword);
        return false;
    }

}

?>
