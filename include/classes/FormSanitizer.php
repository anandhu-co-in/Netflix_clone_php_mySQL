<!-- This Class contains different functions to sanitize strings. Update/Modify them as required if needed -->
<?php

class FormSanitizer{

    public static function sanitizeFormString($inputText){
        return ucfirst(strtolower(trim(strip_tags($inputText))));
    }
    
    public static function sanitizeFormUserName($inputText){
        return ucfirst(strtolower(trim(strip_tags($inputText))));
    }

    public static function sanitizeFormPassword($inputText){
        return ucfirst(strtolower(trim(strip_tags($inputText))));
    }

    public static function sanitizeFormEmail($inputText){
        return ucfirst(strtolower(trim(strip_tags($inputText))));
    }

}



?>