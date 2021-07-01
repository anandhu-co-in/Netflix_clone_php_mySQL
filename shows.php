<?php
require_once("include/header.php");

$preview= new PreviewProvider($con,$userLoggedin);
echo $preview->createTVShowPreviewVideo();

$preview= new CategoryContainers($con,$userLoggedin);
echo $preview->showTVShowCategories();

?>