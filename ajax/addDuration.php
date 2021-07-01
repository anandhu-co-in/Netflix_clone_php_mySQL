<?php

require_once("../include/config.php");

if(isset($_POST["videoId"]) && isset($_POST["username"])){
    echo "SET".$_POST["videoId"].$_POST["username"];
}

$querry=$con->prepare("SELECT * from videoprogress WHERE username=:username and videoid=:videoId");
$querry->bindValue(":username",$_POST["username"]);
$querry->bindValue(":videoId",$_POST["videoId"]);
$querry->execute();

echo $querry->rowCount();

if ($querry->rowCount()==0){
    $querry=$con->prepare("INSERT INTO videoprogress (username,videoid)VALUES(:username,:videoId)");
    $querry->bindValue(":username",$_POST["username"]);
    $querry->bindValue(":videoId",$_POST["videoId"]);
    $querry->execute();
    echo "Progress Row Created";
}
else{
    echo "Progress Row Already Present";
}


?>