<?php
require_once("include/header.php");

$preview= new PreviewProvider($con,$userLoggedin);
echo $preview->createPreviewVideo();

$preview= new CategoryContainers($con,$userLoggedin);
echo $preview->showAllCategories();

?>