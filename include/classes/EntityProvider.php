<?php

class EntityProvider{

    private $result;

    //Get entities from entity table (based on categoryId if passed) and return them as array
    public static function getEntities($con,$categoryId,$limit){

        $sql="SELECT * from entities ";

        if($categoryId != null){
            $sql.="WHERE categoryId=:categoryId ";
        }

        $sql.="ORDER BY RAND() LIMIT :limit";
        $querry=$con->prepare($sql);

        if($categoryId != null){
            $querry->bindValue(":categoryId",$categoryId);
        }

        $querry->bindValue(":limit",$limit,PDO::PARAM_INT);
        $querry->execute();

        $result=array();
        while($row=$querry->fetch(PDO::FETCH_ASSOC)){ 
            $result[]=new Entity($con,$row); //This syntax pushes the new entity as next element into array
        }

        return $result;
    }


    //Get Entities for TV Shows only
    public static function getTVShowsEntities($con,$categoryId,$limit){

        $sql="SELECT DISTINCT(entities.id) 
            FROM entities INNER JOIN videos ON entities.id=videos.entityId
            WHERE videos.isMovie=0 ";

        if($categoryId != null){
            $sql.="AND categoryId=:categoryId ";
        }

        $sql.="ORDER BY RAND() LIMIT :limit";
        $querry=$con->prepare($sql);

        if($categoryId != null){
            $querry->bindValue(":categoryId",$categoryId);
        }

        $querry->bindValue(":limit",$limit,PDO::PARAM_INT);
        $querry->execute();

        $result=array();
        while($row=$querry->fetch(PDO::FETCH_ASSOC)){ 
            $result[]=new Entity($con,$row["id"]);
        
        }

        return $result;
    }


    //Get entities for movies only
    public static function getMoviesEntities($con,$categoryId,$limit){

        $sql="SELECT DISTINCT(entities.id) 
            FROM entities INNER JOIN videos ON entities.id=videos.entityId
            WHERE videos.isMovie=1 ";

        if($categoryId != null){
            $sql.="AND categoryId=:categoryId ";
        }

        $sql.="ORDER BY RAND() LIMIT :limit";
        $querry=$con->prepare($sql);

        if($categoryId != null){
            $querry->bindValue(":categoryId",$categoryId);
        }

        $querry->bindValue(":limit",$limit,PDO::PARAM_INT);
        $querry->execute();

        $result=array();
        while($row=$querry->fetch(PDO::FETCH_ASSOC)){ 

            $result[]=new Entity($con,$row["id"]);
        
        }

        return $result;
    }

    
    //Get entities based on a search term
    public static function getSearchEntities($con,$term){

        $sql="SELECT * FROM entities WHERE name LIKE CONCAT('%',:term,'%') LIMIT 30";

        $querry=$con->prepare($sql);
        $querry->bindValue(":term",$term);
        $querry->execute();

        $result=array();
        while($row=$querry->fetch(PDO::FETCH_ASSOC)){ 

            $result[]=new Entity($con,$row);       

        }

        return $result;
    }

}



?>