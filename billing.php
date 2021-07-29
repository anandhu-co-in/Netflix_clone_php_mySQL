<?php

require_once("include/paypalConfig.php");
require_once("billingPlan.php");

//We will have the billing plan Id at this moment from billing plan.php
$id=$plan->getId();
echo $id;


// 5. Copied code from 'Create billing agreement attribute object' section - https://developer.paypal.com/docs/api/quickstart/create-billing-agreement

use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;

// Create new agreement
$agreement = new Agreement();
$agreement->setName('Subscription to Netflix Clone') //MODIFIED
  ->setDescription('$9 Setup fee and recurring Payments of Rs 79.99') //MODIFIED
  ->setStartDate(gmdate("Y-m-d\TH:i:s\Z",strtotime("+1 month",time())));  //'2020-06-17T9:45:04Z' //Paypal requires we pay after some days,, lets pay after 1 month from today(like a free trial). Also notice the datetime format used here.// So we need to utilize the setup fee for first payment(see billing plan)

// Set plan id
$plan = new Plan();
$plan->setId($id); //MODIFIED - Here i pased the Id we obtained
$agreement->setPlan($plan);

// Add payer type
$payer = new Payer();
$payer->setPaymentMethod('paypal');
$agreement->setPayer($payer);

// // Adding shipping details
// $shippingAddress = new ShippingAddress();
// $shippingAddress->setLine1('111 First Street')
//   ->setCity('Saratoga')
//   ->setState('CA')
//   ->setPostalCode('95070')
//   ->setCountryCode('US');
// $agreement->setShippingAddress($shippingAddress);



//6. Code copied from Create billing agreement (PayPal) https://developer.paypal.com/docs/api/quickstart/create-billing-agreement#create-billing-agreement-paypal
try {
    // Create agreement
    $agreement = $agreement->create($apiContext);
  
    // Extract approval URL to redirect user
    $approvalUrl = $agreement->getApprovalLink();
    header("Location:$approvalUrl"); //MODIFIED - This line is added, navigate to the approval url <-- NEED TO UNDERSTAND

  } catch (PayPal\Exception\PayPalConnectionException $ex) {
    echo $ex->getCode();
    echo $ex->getData();
    die($ex);
  } catch (Exception $ex) {
    die($ex);
  }




?>