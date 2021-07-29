<!-- Paypal Personal account -->

<!-- 
Sandbox account of payer for testing:

sb-l1kmg4786703@personal.example.com
12345678 
This sanbox paypal account is created in my actual paypal developer account

The owner sandbox account used is (You can see this if you open the sandbox app in developer.paypal)
sb-rqyz04732792@business.example.com
123456

You can login to the sandbox accounts(both owner and payer) with above creds if you want, using https://www.sandbox.paypal.com, it will show the balance etc..

-->

<?php

require_once("include/header.php");
require_once("include/classes/Account.php");
require_once("include/classes/FormSanitizer.php");
require_once("include/classes/constants.php");
require_once("include/paypalConfig.php");
require_once("include/classes/BillingDetails.php");

$user = new User($con, $userLoggedin);

$detailsMessage = "";
$passWordMessage = "";
$subscriotionMessage="";

$firstName = isset($_POST["firstName"]) ? $_POST["firstName"] : $user->getFirstName();
$lastName = isset($_POST["lastName"]) ? $_POST["lastName"] : $user->getLastName();
$email = isset($_POST["email"]) ? $_POST["email"] : $user->getEmail();


//If user the save details button is clicked, update first form details to db
if (isset($_POST["saveDetailsButton"])) {
    $account = new Account($con);
    $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
    $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);

    if ($account->updateDetails($firstName, $lastName, $email, $userLoggedin)) {

        $detailsMessage = "<div class='alertSuccess'>
                            Details Updated Successfully
                        </div>";
    } else {
        $errorMessage = $account->getFirstError();
        $detailsMessage = "<div class='alertFail'>
                            $errorMessage
                        </div>";
    }
}


//Same password changing form also
if (isset($_POST["savePasswordButton"])) {

    $account = new Account($con);
    $old_password = FormSanitizer::sanitizeFormPassword($_POST["oldPassword"]);
    $new_password = FormSanitizer::sanitizeFormPassword($_POST["newPassword"]);
    $new_password2 = FormSanitizer::sanitizeFormPassword($_POST["confirmNewPassword"]);

    echo $old_password;
    echo $new_password;
    echo $new_password2;

    if ($account->updatePassword($old_password, $new_password, $new_password2, $userLoggedin)) {

        $passWordMessage = "<div class='alertSuccess'>
                            Details Updated Successfully
                        </div>";
    } else {
        $errorMessage = $account->getFirstError();
        $passWordMessage = "<div class='alertFail'>
                            $errorMessage
                        </div>";
    }
}


//If this is a redirection after a successful paypal payment

//7. Code copied from Execute Billing Agreement (PayPal)  from dev site, quick start, and modified(db uodate, the else, error messages part etc)

if (isset($_GET['success']) && $_GET['success'] == 'true') {

    $token = $_GET['token'];
    $agreement = new \PayPal\Api\Agreement();

    $subscriotionMessage = "<div class='alertFail'>
                                Something went wrong!
                             </div>";

    try {
        // Execute agreement
        $agreement->execute($token, $apiContext);

        //Update to databse
        $result=BillingDetails::insertDetails($con,$agreement,$token,$userLoggedin);
        $result=$result && $user->setIsSubscribed(1);
        echo $result;

        if($result){
            $subscriotionMessage = "<div class='alertSuccess'>
                                        You're all signed up!
                                    </div>";
        }

    } catch (PayPal\Exception\PayPalConnectionException $ex) {
        echo $ex->getCode();
        echo $ex->getData();
        die($ex);
    } catch (Exception $ex) {
        die($ex);
    }

} else if (isset($_GET['success']) && $_GET['success'] == 'false') {

    $subscriotionMessage = "<div class='alertFail'>
                            User cancelled or something went wrong!
                         </div>";
}

?>



<div class="settingsContainer column">

    <div class="formSection">
        <h1>User details</h1>
        <form method="POST">
            <input type="text" name="firstName" value="<?php echo $firstName ?>">
            <input type="text" name="lastName" value="<?php echo $lastName ?>">
            <input type="email" name="email" value="<?php echo $email ?>">
            <?php echo $detailsMessage ?>
            <input type="submit" name="saveDetailsButton" class="button" value="Save">

        </form>
    </div>

    <hr>

    <div class="formSection">
        <h1>Update password</h1>
        <form method="POST">
            <input type="password" name="oldPassword" placeholder="Old password">
            <input type="text" name="newPassword" placeholder="New password">
            <input type="text" name="confirmNewPassword" placeholder="Confirm new password">
            <?php echo $passWordMessage ?>
            <input type="submit" name="savePasswordButton" class="button">
        </form>
    </div>

    <hr>

    <div class="formSection">
        <h1>Subscription</h1>

        <?php echo $subscriotionMessage ?>

        <?php

        if ($user->getIsSubscribed()) {
            echo "<h3>You are subscribed! Go to PayPal to cancel.</h3>";
            echo "<h4>(Cancellation is not yet implemented. If you need to try payment again, Register a new account)</h3>";
        } else {
            echo "<a href='billing.php'>Subscribe to Reeceflix</a>";
        }

        ?>

    </div>

</div>

<!-- I got the paypal php sdk from below link
https://github.com/paypal/PayPal-PHP-SDK/releases 


//PENDING -- EXPLORE CALCELLATION - this cant be done without hosting, as it needs to use paypal webhooks has to listen for changes and then communicate with our site
//Webhook cant communicate with our local host

-->







