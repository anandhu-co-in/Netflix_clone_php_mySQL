<?php

require_once("include/config.php");
require_once("include/classes/PreviewProvider.php");
require_once("include/classes/CategoryContainers.php");
require_once("include/classes/Entity.php");
require_once("include/classes/EntityProvider.php");
require_once("include/classes/ErrorMessage.php");
require_once("include/classes/SeasonProvider.php");
require_once("include/classes/Season.php");
require_once("include/classes/Video.php");
require_once("include/classes/VideoProvider.php");
require_once("include/classes/User.php");

//Redirect to login page, no user logged in sessin is found
if(!isset($_SESSION["userLoggedIn"])){
    header("Location:login.php");
}
$userLoggedin=$_SESSION["userLoggedIn"];

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome to Netflix</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
        <script src="assets/js/script.js"></script>
        <link rel="stylesheet" href="assets/css/style.css">

    </head>
    <body>
        <div class="wrapper">

        <?php 
            if(!isset($dontShowNavbar)){
                require_once("navbar.php");
            }
        ?>


<!-- EVEY PAGE EXCEPT FROM LOGIN/SIGNUP STARTS FROM HERE -->
<!-- FONT AWESOME CDN IS ADDED IN HEAD -->
<!-- ALSO NOTICE THAT WE HAVENT CLOSED THE BODY AND WRAPPER TAGS, PHP AUTOCLOSES THEM -->