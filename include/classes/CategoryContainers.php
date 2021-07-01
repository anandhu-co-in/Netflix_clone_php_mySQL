<?php

class CategoryContainers{

    private $con,$username;

    public function __construct($con,$username)
    {
        $this->con=$con;
        $this->username=$username;
        
    }


    public function showAllCategories(){

        //Get All category rows
        $querry=$this->con->prepare("SELECT * from categories");
        $querry->execute();


        $html="<div class='previewCategories'>";

        //Keep on Appending the HTML OF EACH CATEGORY
        while($row=$querry->fetch(PDO::FETCH_ASSOC)){ //This loop working needs investigation
            $html.=$this->getCategoyHtml($row,null,true,true);
        }

        //Return the whole html to print
        return $html."</div>";
    }


    public function showTVShowCategories(){

        //Get All category rows
        $querry=$this->con->prepare("SELECT * from categories");
        $querry->execute();


        $html="<div class='previewCategories'>
                <h1>TV Shows</h1>";

        //Keep on Appending the HTML OF EACH CATEGORY
        while($row=$querry->fetch(PDO::FETCH_ASSOC)){ //This loop working needs investigation
            $html.=$this->getCategoyHtml($row,null,true,false);
        }

        //Return the whole html to print
        return $html."</div>";
    }


    public function showMovieCategories(){

        //Get All category rows
        $querry=$this->con->prepare("SELECT * from categories");
        $querry->execute();


        $html="<div class='previewCategories'>
                <h1>Movies</h1>";

        //Keep on Appending the HTML OF EACH CATEGORY
        while($row=$querry->fetch(PDO::FETCH_ASSOC)){ //This loop working needs investigation
            $html.=$this->getCategoyHtml($row,null,false,true);
        }

        //Return the whole html to print
        return $html."</div>";
    }



    //Using a category Id Disply all 
    public function showCategory($categoryId,$title=null){

                //Get All category rows
                $querry=$this->con->prepare("SELECT * from categories where id=:id");
                $querry->bindValue(":id",$categoryId);
                $querry->execute();
        

                $html="<div class='previewCategories noScroll'>";
        
                //Keep on Appending the HTML OF EACH CATEGORY
                while($row=$querry->fetch(PDO::FETCH_ASSOC)){ //This loop working needs investigation
                    $html.=$this->getCategoyHtml($row,$title,true,true);
                }
        
                //Return the whole html to print
                return $html."</div>";

    }




    // To generate the HTML for a particular category row
    private function getCategoyHtml($sqlData,$title,$tvShows,$movies){


        // We have the category Id
        $categoryId=$sqlData["id"];

        //Title to use in the html (name of the category or else a passec custom value)
        $title = $title==null ? $sqlData["name"]:$title;


        if($tvShows && $movies){
            $entitis=EntityProvider::getEntities($this->con,$categoryId,30);
        }
        else if($tvShows){
            //Add here for tvshows only
            $entitis=EntityProvider::getTVShowsEntities($this->con,$categoryId,30);
        }
        else{
            //Add here for movies only
            $entitis=EntityProvider::getMoviesEntities($this->con,$categoryId,30);
        }

        if(sizeof($entitis)==0){
            return;
        }

        //Append all the entities to display as a set
        $entitiesHtml="";
        
        $previewProvider=new PreviewProvider($this->con,$this->username);
        foreach($entitis as $entity){
            $entitiesHtml.= $previewProvider->createEntityPreviewSquare($entity);
        
        }

        return "<div class='category'>
                    <a href='category.php?id=$categoryId'>
                        <h3>$title</h3>
                    </a>
                
                    <div class='entities'>
                        $entitiesHtml
                    </div>
                </div>";
        
    }

}


?>

