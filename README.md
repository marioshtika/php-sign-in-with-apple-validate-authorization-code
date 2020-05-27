Sign in with Apple - Validate the authorization code
====================================================

Manage Sign In with Apple and validate the authentication code, server side passed through by the iOS client.

Example
-------

```php
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
```

Generate the client secret
-------
Use this [repository](https://github.com/marioshtika/php-sign-in-with-apple-generate-client-secret) to generate the client secret