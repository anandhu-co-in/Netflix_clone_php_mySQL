<?php
require_once("../include/config.php");
require_once("../include/classes/SearchResultsProvider.php");
require_once("../include/classes/EntityProvider.php");
require_once("../include/classes/Entity.php");
require_once("../include/classes/PreviewProvider.php");

if(isset($_POST["term"]) && isset($_POST["username"])){

   $srp=new SearchResultProvider($con,$_POST["username"]);
   echo $srp->getResults($_POST["term"]);

}

else{

    echo "Progess value not obtained";

}

?>