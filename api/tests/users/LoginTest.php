<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;



class LoginTest extends WebTestCase
{
    private const ENDPOINT = 'api/v1/users/login_check';


    public function testLoginEndpointInvalid()
    {
        $client = static::createClient();

        $payload = [
            'username' => 'test3466@example.com',
            'password' => '123456'
        ];

        $client->request(
            'POST',
            '/api/v1/users/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
            json_encode($payload) // Datos inválidos
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);


        $this->assertTrue(
            in_array($response->getStatusCode(), [400, 405, 404, 409]),
            sprintf('Se esperaba un código de error, pero se obtuvo %s.', $response->getStatusCode()),
            sprintf("Description error: %s\n\n", $response->getStatusCode(), $data['error_message'])
        );
    }


    public function testLoginNoField()
    {

        $client = static::createClient();

        $payload = [
            'username' => 'test3466@example.com',
            #'password' => '123456'
        ];

        $client->request(Request::METHOD_POST, self::ENDPOINT, [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'], json_encode($payload));
        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true);

        $this->assertTrue(
            in_array($response->getStatusCode(), [400, 422]),
            sprintf('Se esperaba un código de error, pero se obtuvo %s.', $response->getStatusCode()),
            sprintf("Description error: %s\n\n", $response->getStatusCode(), $data['error_message'])
        );
    }

    public function testWrongPass()
    {

        $client = static::createClient();

        $payload = [
            'username' => 'test3466@example.com',
            'password' => '34324454353'
        ];

        $client->request(Request::METHOD_POST, self::ENDPOINT, [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'], json_encode($payload));
        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true);
        $errorMessage = '';

        if (isset($data['error_message'])) {
            $errorMessage = sprintf("Error:\n Code: Se esperaba un 400/409, pero se obtuvo %s \n Description error: %s\n\n", $response->getStatusCode(), $data['error_message']);
        }


        $this->assertTrue(
            in_array($response->getStatusCode(), [401, 400, 405]),
            sprintf('No se esperaba un codigo de error, pero se obtuvo %s.', $response->getStatusCode()),
            $errorMessage
        );
    }

    public function testLoginPass()
    {

        $client = static::createClient();

        $payload = [
            'username' => 'test3466@example.com',
            'password' => '123456'
        ];

        $client->request(Request::METHOD_POST, self::ENDPOINT, [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'], json_encode($payload));
        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true);
        $errorMessage = '';

        if (isset($data['error_message'])) {
            $errorMessage = sprintf("Error:\n Code: Se esperaba un 400/409, pero se obtuvo %s \n Description error: %s\n\n", $response->getStatusCode(), $data['error_message']);
        }


        $this->assertTrue(
            in_array($response->getStatusCode(), [200]),
            sprintf('No se esperaba un codigo de error, pero se obtuvo %s.', $response->getStatusCode()),
            $errorMessage
        );
    }
}
