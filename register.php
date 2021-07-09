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

    echo $success

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
    </head>
    <body>
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
                </form>
                <a href="login.php">Already have an account? Login here!</a>
            </div>
        </div>
    </body>
</html>