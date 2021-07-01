<?php

// Entity Class, will have the SQL data row for an entity, and getters from their values

class Entity{

    private $con, $sqlData;


    //This constructure, will create the object with the input sql data itself, or if an id passed, get the entity sql here itself
    public function __construct($con,$input){
    
        $this->con=$con;

        if(is_array($input)){
            $this->sqlData=$input;
        }
        else{
            $querry=$this->con->prepare("SELECT * from entities WHERE id=:id");
            $querry->bindValue(":id",$input);
            $querry->execute();
            $this->sqlData=$querry->fetch(PDO::FETCH_ASSOC);
        }
    }


    //Getters for this entity
    public function getName(){
        return $this->sqlData["name"];
    }

    public function getThumbnail(){
        return $this->sqlData["thumbnail"];
    }

    public function getPreview(){
        return $this->sqlData["preview"];
    }

    public function getId(){
        return $this->sqlData["id"];
    }


    public function getCategoryId(){
        return $this->sqlData["categoryId"];
    }


    //Get seasons for the current entity
    public function getSeasons(){

        //Get the rows from videos table for the entity Id, ordered by seasons,episode
        $querry=$this->con->prepare("SELECT * FROM videos WHERE entityId=:id AND isMovie=0 ORDER BY season,episode ASC");
        $querry->bindValue(":id",$this->getId());
        $querry->execute();

        $seasons=array();
        $videos=array();
        $currentSeason=null;

        //Loop through the rows
        while($row=$querry->fetch(PDO::FETCH_ASSOC)){

            //If we encountered a row with new season
            if($currentSeason!=null && $currentSeason!=$row["season"]){

                //Created a season variable with the current videos we encountered and the season number
                $seasons[]=new Season($currentSeason,$videos);
                $videos=array();
            }

            // We have current rows season variable. Keep storing its video
            $currentSeason=$row["season"];
            $videos[]=new Video($this->con,$row);
        }
        
        //Since the last season rows wont fall in above, loop, lets write it separately
        if(sizeof($videos)){
            $seasons[]=new Season($currentSeason,$videos);
        }

        //Return the array of seasons, it element has its season number and video object array
        return $seasons;

    }

}


?>