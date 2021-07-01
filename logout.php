<?php

// We destroy the sesssion and renavigate to logout.. sesssion.start here becasue we want the session availeble here to destroy it

session_start();
session_destroy();
header("Location:login.php");

?>