<?php
require_once("include/header.php");

$preview= new PreviewProvider($con,$userLoggedin);
echo $preview->createPreviewVideo();

$preview= new CategoryContainers($con,$userLoggedin);
echo $preview->showAllCategories();

?>



<!-- Notes : Paypal flow starts from payalConfig.php class. I have added comments with numbers to easily follow -->