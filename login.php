<?php 

require_once("include/config.php");
require_once("include/classes/Account.php");
require_once("include/classes/constants.php");
require_once("include/classes/FormSanitizer.php");
require_once("include/classes/EntityProvider.php");

$account=new Account($con);

//If user just clicked submit button on login page, perform login
if(isset($_POST["submitButton"])){

    $userName=FormSanitizer::sanitizeFormString($_POST["userName"]);
    $password=FormSanitizer::sanitizeFormPassword($_POST["password"]);
    $success=$account->login($userName,$password);
    if($success){
        $_SESSION["userLoggedIn"]=$userName; //Set the session variable
        header("Location:index.php");

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
                <h2>Sign In</h2>
                <h4>to continue to Netflix</h4>
                <form action="" method="POST">
                    <?php echo $account->getError(Constants::$loginFailed)?> <!-- If login failed error exists, it will appear here -->
                    <input type="text" name="userName" placeholder="User Name" required>
                    <input type="password" name="password" placeholder="Password"required>
                    <input type="submit" name="submitButton" value="Login">
                </form>
                <a href="register.php">Don't have an account? Register here!</a>
            </div>
        </div>
    </body>
</html>