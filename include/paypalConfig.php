<?php

// 1.Include autoload.php from the downloaded paypal SDK
require_once("PayPal-PHP-SDK/autoload.php");


//2.I can login to https://developer.paypal.com/ with my real paypal account mail.***.com/pwd. There we can see sandbox and live apps
//2.Create app in paypal sandbox and get the client aid and sectret, and use below code to authenticate(google for "php paypal apicontext making first call")
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'Ae3QbarzMNFjGaT9Hm6jApLIPGbv75Rkc-C5St0oRdj-l2KoyJVcDzFy0UN4n_e9Jcghr-teQWAfhXQu',     // ClientID
        'EOpjWyJli_ma8W-S2D4Qoq5lzv4ULPzwohrGH-9dLtd-Mjbc3IUcZUjZQoWHMRXJV_HD9i6ilt_eSrUX'      // ClientSecret
    )
);

?>




<?php

//1.Billing Plan - We specifiy the amount, recurrences and such transaction details etc.
//2.Billing Agreement - Users use this to actually make the transaction using the billing plan



?>