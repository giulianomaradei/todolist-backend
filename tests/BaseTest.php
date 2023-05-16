<?php

namespace Tests;

class BaseTest extends TestCase
{

    /**
     * Return Header Authorization Bearer function
     *
     * @return void
     */
    public function getHeaderAutorization()
    {
        $accessToken = $this->getToken();
        return ['Authorization' => "Bearer {$accessToken->access_token}"];
    }

    public function getToken()
    {
        $credentials = self::getCredentials();
        $baseUrl = env('API_URL');
        $response = $this->postJson("$baseUrl/api/login", $credentials);

        return json_decode($response->getContent());
    }

    public static function getCredentials()
    {
        return [
            'email'    => env('APP_TEST_EMAIL'),
            'password' => env('APP_TEST_PASSWORD'),
        ];
    }
}