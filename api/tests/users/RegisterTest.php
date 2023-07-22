<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RegisterTest extends WebTestCase
{
    private const ENDPOINT = 'api/v1/users/register';


    public function testRegistrationEndpointWithInvalidData()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/v1/users/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
            '{"invalid": "data"}'  // Datos inválidos
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);


        $this->assertTrue(
            in_array($response->getStatusCode(), [400, 500]),
            sprintf('Se esperaba un código de error, pero se obtuvo %s.', $response->getStatusCode()),
            sprintf("Description error: %s\n\n", $response->getStatusCode(), $data['error_message'])
        );

        $this->assertEquals(500, $response->getStatusCode());
    }


    public function testRegistration()
    {
        $client = static::createClient();

        $randomNum = rand(0, 10000); // generamos un número aleatorio entre 0 y 10000

        $payload = [
            'name' => 'test_' . $randomNum, // añadimos el número aleatorio al nombre
            'surname' => 'testeo_' . $randomNum, // añadimos el número aleatorio al apellido
            'email' => 'test' . $randomNum . "@example.com", // añadimos el número aleatorio al correo electrónico
            'password' => '123456',
            'username' => 'test_' . $randomNum // añadimos el número aleatorio al nombre de usuario
        ];
        //echo json_encode($payload);


        $client->request(Request::METHOD_POST, self::ENDPOINT, [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'], json_encode($payload));
        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true);

        /* fwrite(STDERR, print_r(json_encode($payload), TRUE)); */

        $statusCodeCheck = in_array($response->getStatusCode(), [200, 201]);
        $errorMessage = 'Contact with the administrator, something went wrong';
        //$this->assertEquals(201, $response->getStatusCode());

        if (isset($data['hydra:description'])) {
            $errorMessage = sprintf("Error: \n Code: Se esperaba 200/201, pero se obtuvo %s \n Description error: %s\n\n ", $response->getStatusCode(), $data['hydra:description']);
        }

        if (isset($data['message'])) {
            $errorMessage = sprintf("Error:\n Code: Se esperaba un 200/201, pero se obtuvo %s \n Description error: %s\n\n", $response->getStatusCode(), $data['error_message']);
        }

        //$this->assertTrue($statusCodeCheck, $errorMessage);
        if (201 === $response->getStatusCode()) {
            fwrite(STDERR, print_r("\nNew user created: \n" . json_encode($data), TRUE));
        }

        $this->assertTrue(in_array($response->getStatusCode(), [200, 201]), $errorMessage);
    }

    public function testAlreadyExists()
    {
        $client = static::createClient();

        //FAIL with email or username
        $payload = [
            'name' => 'Eric',
            'surname' => 'Ocaña Segura',
            'email' => 'root@gmail.com',
            'password' => '123456',
            'username' => 'eric.ocana',
        ];

        $client->request(Request::METHOD_POST, self::ENDPOINT, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $statusCodeCheck = in_array($response->getStatusCode(), [400, 409]);
        $errorMessage = 'Contact with the administrator, something went wrong';

        if (isset($data['hydra:description'])) {
            $errorMessage = sprintf("Error: \n Code: Se esperaba 400/409, pero se obtuvo %s \n Description error: %s\n\n ", $response->getStatusCode(), $data['hydra:description']);
        }

        if (isset($data['message'])) {
            $errorMessage = sprintf("Error:\n Code: Se esperaba un 400/409, pero se obtuvo %s \n Description error: %s\n\n", $response->getStatusCode(), $data['error_message']);
        }

        $this->assertTrue($statusCodeCheck, $errorMessage,);
    }
}
