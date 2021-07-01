<?php

require_once("../include/config.php");

if(isset($_POST["videoId"]) && isset($_POST["username"]) && isset($_POST["username"])){
    
    $querry=$con->prepare("UPDATE videoprogress SET progress=:progress, datemodified=NOW() WHERE username=:username and videoid=:videoId");
    $querry->bindValue(":progress",$_POST["progress"]);
    $querry->bindValue(":username",$_POST["username"]);
    $querry->bindValue(":videoId",$_POST["videoId"]);
    $querry->execute();
    echo "Progress Updated";
}

else{
    echo "Progress not Updated";
}


?>