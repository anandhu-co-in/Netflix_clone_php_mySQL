<?php

require_once("../include/config.php");

if(isset($_POST["videoId"]) && isset($_POST["username"]) && isset($_POST["username"])){
    
    $querry=$con->prepare("UPDATE videoprogress SET finished=1, progress=0 WHERE username=:username and videoid=:videoId");
    $querry->bindValue(":username",$_POST["username"]);
    $querry->bindValue(":videoId",$_POST["videoId"]);
    $querry->execute();
    echo "Marked as Finished and reset progress";
}

else{
    echo "Not marked finished";
}


?>