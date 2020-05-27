<?php

require_once('includes/apple-signin-validate.php');

try {
    $authorizationCode = ''; // From request
    $userUniqueIdentifier = ''; // From request
    $appleClientID = ''; // App ID
    $appleClientSecret = ''; // The generated client secret

    $appleSignIn = new AppleSignInValidate($authorizationCode, $appleClientID, $appleClientSecret);
    if ($appleSignIn->isValidToken()) {
        if ($appleSignIn->isValidClientId($appleClientID)) {
            if ($appleSignIn->isValidUserUniqueIdentifier($userUniqueIdentifier)) {
                echo 'Valid authorization code';
            } else {
                echo 'Wrong user';
            }
        } else {
            echo 'Wrong client ID';
        }
    } else if ($appleSignIn->isTokenError()) {
        echo 'Error token (' . $appleSignIn->getToken()->error . ')';
    } else {
        echo 'Something went wrong';
    }
} catch (Exception $error) {
    echo $error->getMessage();
}
