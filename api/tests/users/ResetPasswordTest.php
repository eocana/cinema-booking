<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;



class ResetPasswordTest extends WebTestCase
{
    private const ENDPOINT = '/api/v1/users/request_reset_password';

    /*  public function testResetPasswordEndpoint()
    { 
        $client = static::createClient();

        $payload = [
            'email' => 'test3466@example.com'
        ];

        $client->request(Request::METHOD_POST, self::ENDPOINT, [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'], json_encode($payload));
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertTrue(
            in_array($response->getStatusCode(), [200]),
            sprintf("Error:\n Code: Se esperaba 200, pero se obtuvo %s \n Description error: %s\n Class: %s\n\n", $response->getStatusCode(), $data['error_message'], $data['class'])
        );
    } */

    public function testResetPasswordEndpointWithInvalidData()
    {
        $client = static::createClient();

        $payload = [
            'email' => 'noexisto@example.com'
        ];

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
    }
}
