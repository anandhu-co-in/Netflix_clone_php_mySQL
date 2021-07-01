<?php
require_once("../include/config.php");

if(isset($_POST["videoId"]) && isset($_POST["username"])){
    // echo "SET".$_POST["videoId"].$_POST["username"];

$querry=$con->prepare("SELECT progress from videoprogress WHERE username=:username and videoid=:videoId");
$querry->bindValue(":username",$_POST["username"]);
$querry->bindValue(":videoId",$_POST["videoId"]);
$querry->execute();


    if ($querry->rowCount()==1){
        echo $querry->fetchColumn();
    }

}
else{
    echo "Progess value not obtained";
}
?>