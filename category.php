<?php
require_once("include/header.php");

if(!isset($_GET["id"])){
    ErrorMessage::show("No Id Passed to Category Page");
}

$preview= new PreviewProvider($con,$userLoggedin);
echo $preview->createCategoryPreviewVideo($_GET["id"]);

$preview= new CategoryContainers($con,$userLoggedin);
echo $preview->showCategory($_GET["id"]);

?>