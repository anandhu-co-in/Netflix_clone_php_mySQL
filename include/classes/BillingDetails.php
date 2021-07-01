<?php

class BillingDetails{

    public static function insertDetails($con,$agreement,$token,$userLoggedin){

        $querry=$con->prepare("INSERT INTO billingDetails (agreementId,nextBillingDate,token,username) VALUES(:agreementId,:nextBillingDate,:token,:username)");
        
        $agreementDetails=$agreement->getAgreementDetails();//paypal gave this function

        $querry->bindValue(":agreementId",$agreement->getId());
        $querry->bindValue(":nextBillingDate",$agreementDetails->getNextBillingDate());
        $querry->bindValue(":token",$token);
        $querry->bindValue(":username",$userLoggedin);

        return $querry->execute();

    }


}


?>