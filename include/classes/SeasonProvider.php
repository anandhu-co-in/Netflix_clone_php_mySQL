<?php


class SeasonProvider{

    private $con, $username;

    public function __construct($con,$username)
    {
        $this->con=$con;
        $this->username=$username;
    }


    //For an entity generated the seasons section
    public function create($entity){

        //Get seasons fot the entity
        $seasons=$entity->getSeasons();
        //Define to get seasons for that entity

        if(sizeof($seasons)==0){
            // echo "No seasons present for this entity";
            return;
        }

        
        $seasonsHtml="";
        
        //For each season
        foreach($seasons as $season){
            $seasonsNumber= $season->getSeasonNumber();

            //Create html for all videos in the season, by looping through the videos within the season
            $videoHtml="";
            foreach($season->getVideos() as $video){
                $videoHtml.=$this->createVideoSquare($video);

            }

            #Add a new html section fof the current season
            $seasonsHtml.=  "<div class='season'>
                                <h3>Season $seasonsNumber</h3>
                                <div class='videos'>
                                    $videoHtml
                                </div>
                            </div>";
        }

        return $seasonsHtml;
    }


    //Generated HTML box for a video object
    private function createVideoSquare($video){
        $id=$video->getId();
        $thumbnail=$video->getThumbnail();
        $name=$video->getTitle();
        $description=$video->getDescription();
        $episodeNumber=$video->getEpisodeNumber();

        $hasSeen=$video->hasSeen($this->username)?"<i class='fas fa-check-circle seen'></i>":"";

        return "<a href='watch.php?id=$id'>
                    <div class='episodeContainer'>
                        <div class='contents'>
                            <img src='$thumbnail' alt=''>
                            <div class='videoInfo'>
                                <h4>$episodeNumber. $name</h4>
                                <span>$description</span>
                            </div>
                            $hasSeen
                        </div>
                    </div>
                </a>";
    }
}


?>
