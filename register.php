<?php 

require_once("include/classes/FormSanitizer.php");
require_once("include/config.php");
require_once("include/classes/Account.php");
require_once("include/classes/constants.php");

$account=new Account($con);

if(isset($_POST["submitButton"])){

    $firstName=FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastName=FormSanitizer::sanitizeFormString($_POST["lastName"]);
    $userName=FormSanitizer::sanitizeFormString($_POST["userName"]);
    $email=FormSanitizer::sanitizeFormEmail($_POST["email"]);
    $email2=FormSanitizer::sanitizeFormEmail($_POST["email2"]);
    $password=FormSanitizer::sanitizeFormPassword($_POST["password"]);
    $password2=FormSanitizer::sanitizeFormPassword($_POST["password2"]);

    $success=$account->register($firstName,$lastName,$userName,$email,$email2,$password,$password2);

    if($success){
        $_SESSION["userLoggedIn"]=$userName; //We can check this on any page of the website
        header("Location:index.php");
    }
}


//Add thse same functioanlity to login page if needed
function getPreviousInputFieldValue($fieldName){
    if(isset($_POST[$fieldName])){
        echo $_POST[$fieldName];
    }
}

?> 

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome to Netflix</title>
        <link rel="stylesheet" href="assets/css/style.css">
                
        <!-- Fav icons generated using https://favicon.io/ -->
        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
    </head>
    <body>

        <p class="devnotes">WARNING : This is a demo application. Please do not post any sensitive information in this. © mail.anandhu@gmail.com<br/></p>

        <div class="signInContainer">
            <div class="login_box">
                <img src="assets/img/netflixlogo.png" alt="Netflix Logo">
                <h2>Sign Up</h2>
                <h4>to continue to Netflix</h4>
                <form action="" method="POST">
                    <?php echo $account->getError(Constants::$firstNameCharectors)?>
                    <input type="text" name="firstName" placeholder="First Name" required value=<?php getPreviousInputFieldValue("firstName") ?>>
                    <?php echo $account->getError(Constants::$lastNameCharectors)?>
                    <input type="text" name="lastName" placeholder="Last Name"required value=<?php getPreviousInputFieldValue("lastName") ?>>
                    <?php echo $account->getError(Constants::$userNameCharectors)?>
                    <?php echo $account->getError(Constants::$userNameAlreadyExits)?>
                    <input type="text" name="userName" placeholder="User Name" required value=<?php getPreviousInputFieldValue("userName") ?>>
                    <?php echo $account->getError(Constants::$emailsDoesntMatch)?>
                    <?php echo $account->getError(Constants::$invalidEmail)?>
                    <?php echo $account->getError(Constants::$emailAlreadyExit)?>
                    <input type="email" name="email" placeholder="Email"required value=<?php getPreviousInputFieldValue("email") ?>>
                    <input type="email" name="email2" placeholder="Confirm Email"required value=<?php getPreviousInputFieldValue("email2") ?>>
                    <?php echo $account->getError(Constants::$passwordDoesntMatch)?>
                    <input type="password" name="password" placeholder="Password"required>
                    <input type="password" name="password2" placeholder="Confirm Password"required>
                    <input type="submit" name="submitButton" value="SUBMIT">
                    <!-- <p><br/>Hi, The registration form stoped working after i hosted this on heroku and i am working on it!, for now please login with Anandhu/123 (Please do not change password!!)Paypal subsciption is alreay done for this account, so you wont be able to try that feature! Apologies!</p> -->
                </form>
                <a href="login.php">Already have an account? Login here!</a>
            </div>
        </div>
    </body>
</html>