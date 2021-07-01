<?php

class Video{

    private $con, $sqlData,$entity;


    //Create video objec, either we can pass the video table row, or just video Id for this constructer to pull the row here itself
    public function __construct($con,$input){

        $this->con=$con;

        if(is_array($input)){
            $this->sqlData=$input;
        }
        else{
            $querry=$this->con->prepare("SELECT * from videos WHERE id=:id");
            $querry->bindValue(":id",$input);
            $querry->execute();
            $this->sqlData=$querry->fetch(PDO::FETCH_ASSOC);
        }

        $this->entity=new Entity($con,$this->sqlData["entityId"]);
    }


    //Getters for all the columns in video table

    public function getId(){
        return $this->sqlData["id"];
    }

    public function getTitle(){
        return $this->sqlData["title"];
    }

    public function getDescription(){
        return $this->sqlData["description"];
    }

    public function getFilePath(){
        return $this->sqlData["filePath"];
    }

    public function getThumbnail(){
        return $this->entity->getThumbnail();
    }

    public function getEpisodeNumber(){
        return $this->sqlData["episode"];
    }

    public function getSeasonNumber(){
        return $this->sqlData["season"];
    }

    public function getEntityId(){
        return $this->sqlData["entityId"];
    }

    public function isMovie(){
        return $this->sqlData["isMovie"]==1;
    }

    //Increament the views count of the video in videos table
    public function incrementViews(){
        $querry=$this->con->prepare("UPDATE videos set views=views+1 WHERE id=:id");
        $querry->bindValue(":id",$this->getId());
        $querry->execute();
    }

    //Return the season episode string of the video
    public function getSeasonAndEpisode(){
        if($this->isMovie()){
            return;
        }
        $season=$this->getSeasonNumber();
        $episode=$this->getEpisodeNumber();
        return "Season $season, Episode $episode";
    }


    //Return true if this video is in progress for the current user
    public function isInProgress($username){
        $querry=$this->con->prepare("SELECT * from videoprogress WHERE videoId=:videoid AND username=:username");
        $querry->bindValue(":videoid",$this->getId());
        $querry->bindValue(":username",$username);
        $querry->execute();
        return $querry->rowCount()!=0;
    }

    //Return true if this video is already finished by the user
    public function hasSeen($username){
        $querry=$this->con->prepare("SELECT * from videoprogress WHERE videoId=:videoid AND username=:username AND finished=1");
        $querry->bindValue(":videoid",$this->getId());
        $querry->bindValue(":username",$username);
        $querry->execute();
        return $querry->rowCount()!=0;
    }


}

?>