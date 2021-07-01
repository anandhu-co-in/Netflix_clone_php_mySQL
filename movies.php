<?php
require_once("include/header.php");

$preview= new PreviewProvider($con,$userLoggedin);
echo $preview->createMoviesPreviewVideo();

$preview= new CategoryContainers($con,$userLoggedin);
echo $preview->showMovieCategories();

?>