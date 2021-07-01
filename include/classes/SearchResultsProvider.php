<?php

class SearchResultProvider{

    private $con,$username;

    //Create preview provider objec with con and username
    public function __construct($con,$un)
    {
        $this->con=$con;
        $this->username=$un;    
    }

    public function getResults($term){

        // echo "RESULTS";
        $entitis=EntityProvider::getSearchEntities($this->con,$term);
        // echo count($entitis);

        $html="<div class='previewCategories noScroll'>";
        $html.=$this->getResultHTML($entitis);
        return $html."</div>";

    }


    private function getResultHTML($entitis){

        if(sizeof($entitis)==0){
            // echo "NO ENTITIES";
            return;
        }

        //Append all the entities to display as a set
        $entitiesHtml="";
        
        $previewProvider=new PreviewProvider($this->con,$this->username);
        foreach($entitis as $entity){
            $entitiesHtml.= $previewProvider->createEntityPreviewSquare($entity);
        }

        return "<div class='category'>              
                    <div class='entities'>
                        $entitiesHtml
                    </div>
                </div>";
    }



}
?>