<!-- We need to generate preview video html, for 

1.The home page (any random video)
2.TV Shows Page (Any tv show)
3.Movies Page (Any movie)
4.Entity Page (Last watched episode) -->


<?php

class PreviewProvider{

    private $con,$username;

    public function __construct($con,$un)
    {
        $this->con=$con;
        $this->username=$un;    
    }


    public function createTVShowPreviewVideo(){
        $entitiesArray=EntityProvider::getTVShowsEntities($this->con,null,1);
        
        if(sizeof($entitiesArray)==0){
            ErrorMessage::show("No error message to display");
        }

        return $this->createPreviewVideo($entitiesArray[0]);
    }

    public function createMoviesPreviewVideo(){
        $entitiesArray=EntityProvider::getMoviesEntities($this->con,null,1);
        
        if(sizeof($entitiesArray)==0){
            ErrorMessage::show("No error message to display");
        }

        return $this->createPreviewVideo($entitiesArray[0]);
    }


    public function createCategoryPreviewVideo($categoryId){
        $entitiesArray=EntityProvider::getEntities($this->con,$categoryId,1);
        
        if(sizeof($entitiesArray)==0){
            ErrorMessage::show("No error message to display");
        }

        return $this->createPreviewVideo($entitiesArray[0]);
    }


    //Create the preview Video HTML using the enttity/random entity and return it
    public function createPreviewVideo($entity=null){
        
        if($entity==null){
            $entity=$this->randomEntity();
        }

        $name=$entity->getName();
        $preview=$entity->getPreview();
        // $preview='http://techslides.com/demos/sample-videos/small.mp4'; //FOR THIS INSTEAD OF ABOVE LINE FOR USING AN ONLINE VIDEO 
        $thumbnail=$entity->getThumbnail();
        $id=$entity->getId();

        // Get the video id of the last watched video on this entity or if not present this returns the first video
        $videoId=VideoProvider::getEntityVideoForUser($this->con,$id,$this->username);

        $video=new Video($this->con,$videoId);

        $inProgress=$video->isInProgress($this->username);
        $playButtonText=$inProgress?"Continue watching":"Play";

        $seasonEpisode=$video->getSeasonAndEpisode();
        $subHeading=$video->isMovie() ? "":"<h4>$seasonEpisode</h4>";
        
        return "<div class='previewContainer'>
                    <img src='$thumbnail' alt='previewImage' class='previewImage' hidden>
                    <video autoplay muted class='previewVideo'>
                        <source src='$preview', type='video/mp4'>
                    </video>
                    <div class='previewOverlay'>  
                        <div class='mainDetails'>
                            <h1>$name</h1>
                            $subHeading
                            <div>
                                <button type='button' onclick='watchVideo($videoId)' class='buttonPlay'><i class='fa fa-play'></i> $playButtonText</button>
                                <button type='button' class='buttonMute'><i class='fa fa-volume-mute'></i></button>
                            </div>
                        </div>
                    </div>
                </div>";
    }


    //Generate the html for displaying as the entity thumbnails
    public function createEntityPreviewSquare($entity){
        $id=$entity->getId();
        $thumbnail=$entity->getThumbnail();
        $name=$entity->getName();

        return "<a href='entity.php?id=$id'>
                    <div class='previewContainer small'>
                        <img src='$thumbnail' title='$name'>
                    </div>           
                </a>";
    }


    //Return a random entity
    private function randomEntity(){
        //Get entities, (limit results to 1) since its still arry specify [0] to get the entity
        $entity=EntityProvider::getEntities($this->con,null,1);
        return $entity[0];
    }
        
}
?>