
<?php

$dontShowNavbar=True;
require_once("include/header.php");


if(!isset($_GET["id"])){
    ErrorMessage::show("No ID passed into this page");
}

$user=new User($con,$userLoggedin);

if(!$user->getIsSubscribed()){
    ErrorMessage::show("You must be subscribed to see this.<a href='profile.php'>Click here to subscribe</a>");
}

$video=new Video($con,$_GET["id"]);
$video->incrementViews();

$upNextVideo=VideoProvider::getUpNext($con,$video);

?>

<!-- Chrome wont autoplay video if not muted if we diretly open the page with url -->

<div class="watchContainer">

    <div class="videoControls watchNav">
        <button class="watchBackButton"><i class="fas fa-arrow-left"></i></button>

        <div>
            <h1><?php echo $video->getTitle();?></h1>
            <h3><?php echo $video->getSeasonAndEpisode();?></h3>
        </div>

    </div>

    <div class="videoControls upNext" style="display: none;" >
        <button class="replayButton"><i class="fas fa-redo"></i></button>
        <div>
            <h1><?php echo $upNextVideo->getTitle();?></h1>
            <h3><?php echo $upNextVideo->getSeasonAndEpisode();?></h3>
            <button class="playNextButton" onclick="watchVideo(<?php echo $upNextVideo->getId();?>)"><i class="fas fa-play"></i></button>
        </div>
    </div>

    <video controls autoplay> 
        <source src="<?php echo $video->getFilePath(); ?>" type="video/mp4">
    </video>
</div>

<script>
    navAutoHide();
    videoProgressTrack("<?php echo $video->getId(); ?>","<?php echo $userLoggedin; ?>");
</script>