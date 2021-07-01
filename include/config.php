<?php
ob_start(); //Turn on output buffering, make sure all the php is executed before outputing the page
session_start(); //What is session?-
date_default_timezone_set("Europe/London"); //

// Connect to db, this conn object will be availeble in all othe pages were we import his config.php
try{

    $con= new PDO("mysql:dbname=netflixdb;host=localhost","root","");
    $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING); # Turn on Error reporting

}
catch(PDOException $e){
    exit("Connection Failed :".$e->getMessage());
}

?>