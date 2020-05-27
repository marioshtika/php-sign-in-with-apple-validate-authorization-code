<?php

class AppleSignInValidate
{
    protected $token;
    protected $claims;

    public function __construct($authorizationCode, $appleClientID, $appleClientSecret)
    {
        $this->token = $this->generateToken($authorizationCode, $appleClientID, $appleClientSecret);
        $this->claims = $this->getClaims();
    }

    private function generateToken($authorizationCode, $appleClientID, $appleClientSecret)
    {
        // https://developer.apple.com/documentation/signinwithapplerestapi/generate_and_validate_tokens
        $url = 'https://appleid.apple.com/auth/token';
        $params = [
            'grant_type' => 'authorization_code',
            'code' => $authorizationCode,
            'client_id' => $appleClientID,
            'client_secret' => $appleClientSecret,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($params) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'User-Agent: curl', # Apple requires a user agent header at the token endpoint
        ]);
        $response = curl_exec($ch);

        return json_decode($response);
    }

    private function getClaims()
    {
        $claims = explode('.', $this->token->id_token)[1];

        return json_decode(base64_decode($claims));
    }

    public function getToken()
    {
        return $this->token;
    }

    public function isValidToken()
    {
        return property_exists($this->token, 'id_token');
    }

    public function isTokenError()
    {
        return property_exists($this->token, 'error');
    }

    public function isValidClientId($appleClientID)
    {
        return $this->claims->aud === $appleClientID;
    }

    public function isValidUserUniqueIdentifier($userUniqueIdentifier)
    {
        return $this->claims->sub === $userUniqueIdentifier;
    }
}
