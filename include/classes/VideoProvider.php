<?php

class VideoProvider{

    //If a vedio is passed, get the next vedio(next episode, next seasons first episode)
    public static function getUpNext($con,$currentVideo){

        $querry=$con->prepare("SELECT * from videos WHERE entityId=:entityId AND id!=:videoId AND((season=:season AND episode>:episode) OR season > :season) ORDER BY season,episode ASC LIMIT 1" );
        $querry->bindValue(":entityId",$currentVideo->getEntityId());
        $querry->bindValue(":videoId",$currentVideo->getId());
        $querry->bindValue(":season",$currentVideo->getSeasonNumber());
        $querry->bindValue(":episode",$currentVideo->getEpisodeNumber());

        $querry->execute();

        //In case of movies, above should return no rows, lets handle it here
        if ($querry->rowCount()==0){
            $querry=$con->prepare("SELECT * from videos WHERE season <=1 AND episode<=1 AND id!=:videoId  ORDER BY views ASC LIMIT 1" );
            $querry->bindValue(":videoId",$currentVideo->getId());   
            $querry->execute();
        }

        $row=$querry->fetch(PDO::FETCH_ASSOC);
        return new Video($con,$row);

    }


    //Either return the users last watched video for the user or else retutn the first seasons first episode
    public static function getEntityVideoForUser($con,$entityId,$userName){
 
        $querry=$con->prepare("SELECT videoid FROM videoprogress INNER JOIN videos 
                               ON videoprogress.videoid=videos.id
                               WHERE username=:username AND
                               videos.entityId=:entityId
                               ORDER BY videoprogress.dateModified DESC
                               LIMIT 1" );
       
        $querry->bindValue(":entityId",$entityId);
        $querry->bindValue(":username",$userName);
        $querry->execute();

        if ($querry->rowCount()==0){

            $querry=$con->prepare("SELECT id from videos WHERE entityId=:entityId ORDER BY season,episode ASC LIMIT 1" );
            $querry->bindValue(":entityId",$entityId);   
            $querry->execute();
    
        }

        return $querry->fetchColumn();

    }
}

?>
