
<?php
require_once("include/header.php");


if(!isset($_GET["id"])){
    ErrorMessage::show("No ID passed into this page");
}

// We have the entity Id from the URL
$entityId=$_GET["id"];

// Get the entity using the entity Id
$entity=new Entity($con,$entityId);

//Display Preview Video by using the entity
$preview= new PreviewProvider($con,$userLoggedin);
echo $preview->createPreviewVideo($entity);

//Dispaly seasons using the entity
$seasonsProvider=new SeasonProvider($con,$userLoggedin);
echo $seasonsProvider->create($entity);

//Show shows with same category Id
$categoryContainers=new CategoryContainers($con,$userLoggedin);
echo $categoryContainers->showCategory($entity->getCategoryId(),"Yout Might Also Like");

?>