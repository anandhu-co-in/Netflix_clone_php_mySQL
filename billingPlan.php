<?php

//3.Code form Paypal developer site SDK->Quick Start -> Define Billing Plan object. See comments for values i have MODIFIED


require_once("include/paypalConfig.php");

use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;

// Create a new billing plan
$plan = new Plan();
$plan->setName('Netflix clone monthly subscription')   //MODIFIED HERE
  ->setDescription('Get you all the features of the site') //MODIFIED HERE
  ->setType('INFINITE'); //MODIFIED HERE - I changed to INFINITE to make the payment going forever

// Set billing plan definitions
$paymentDefinition = new PaymentDefinition();
$paymentDefinition->setName('Regular Payments')
  ->setType('REGULAR')
  ->setFrequency('Month')
  ->setFrequencyInterval('1') //MODIFIED
  ->setAmount(new Currency(array('value' => 70.99, 'currency' => 'USD'))); //MODIFIED, You can get the currency codes from paypal SDK

//Set charge models- SHIPPPING RELATED, LETS IGNORE THIS SECTION

// $chargeModel = new ChargeModel();
// $chargeModel->setType('SHIPPING')
//   ->setAmount(new Currency(array('value' => 10, 'currency' => 'USD')));
// $paymentDefinition->setChargeModels(array($chargeModel));
// Set merchant preferences



$currentURL="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";  //THIS IS THE RETURN URL back from paypal. this expression will give www.netflixclone.com/billing.php, from there we replace billling.php
$returnURL=str_replace("billing.php","profile.php",$currentURL); //Note : Hack this url as you wish, for example, you want to go to a success page


$merchantPreferences = new MerchantPreferences();
$merchantPreferences->setReturnUrl($returnURL."?success=true")  //MODIFIED - Our able return url passed here
  ->setCancelUrl($returnURL."?success=false") //MODIFIED - Our able return url passed here.. below options keep same
  ->setAutoBillAmount('yes')
  ->setInitialFailAmountAction('CONTINUE')
  ->setMaxFailAttempts('0')
  ->setSetupFee(new Currency(array('value' => 9, 'currency' => 'USD'))); //This is a setup fee and will be charged instantly. So i can avoid the free trial using this

$plan->setPaymentDefinitions(array($paymentDefinition));
$plan->setMerchantPreferences($merchantPreferences);



//4.By default billing plans are not active, we need to activate this using the below code (SDK->Quick Start -> Create and activate billing plan)
//HAVENT MODIFIED ANYTHING,JUST COPY PASTED 
//If you open this page billingplan.php, then you can see the BILLING PLAN ID echoed, this is passed to billing agreement later

try {
    $createdPlan = $plan->create($apiContext);
  
    try {
      $patch = new Patch();
      $value = new PayPalModel('{"state":"ACTIVE"}');
      $patch->setOp('replace')
        ->setPath('/')
        ->setValue($value);
      $patchRequest = new PatchRequest();
      $patchRequest->addPatch($patch);
      $createdPlan->update($patchRequest, $apiContext);
      $plan = Plan::get($createdPlan->getId(), $apiContext);
  
      // Output plan id
      echo $plan->getId();
    } catch (PayPal\Exception\PayPalConnectionException $ex) {
      echo $ex->getCode();
      echo $ex->getData();
      die($ex);
    } catch (Exception $ex) {
      die($ex);
    }
  } catch (PayPal\Exception\PayPalConnectionException $ex) {
    echo $ex->getCode();
    echo $ex->getData();
    die($ex);
  } catch (Exception $ex) {
    die($ex);
  }


?>