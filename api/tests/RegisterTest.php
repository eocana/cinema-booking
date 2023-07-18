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
                ['CONTENT_TYPE' => 'application/json'],
                '{"invalid": "data"}'  // Datos inválidos
            );

            $response = $client->getResponse();
            $data = json_decode($response->getContent(), true);
           
            
            $this->assertTrue(
                in_array($response->getStatusCode(), [400, 422]), 
                sprintf('Se esperaba un código de error, pero se obtuvo %s.', $response->getStatusCode()),
                sprintf('Error description %s.', $data['hydra:description']),
            );

            $this->assertEquals(400, $response->getStatusCode());
    }

    
    public function testRegistration()
    {
        $client = static::createClient();

         $randomNum = rand(0, 10000); // generamos un número aleatorio entre 0 y 10000

        $payload = [
            'name' => 'test_' . $randomNum, // añadimos el número aleatorio al nombre
            'surname' => 'testeo_' . $randomNum, // añadimos el número aleatorio al apellido
            'email' => 'test_' . $randomNum . '@example.com', // añadimos el número aleatorio al correo electrónico
            'password' => '1234',
            'username' => 'test_' . $randomNum // añadimos el número aleatorio al nombre de usuario
        ];
        //echo json_encode($payload);
        // Realiza la petición de registro
        $client->request(Request::METHOD_POST, self::ENDPOINT, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
       // Comprueba los datos de la respuesta JSON

       $this->assertTrue(
                in_array($response->getStatusCode(), [201, 200]),
                sprintf("Error: \n Code: Se esperaba 200/201, pero se obtuvo %s \n Description error: %s\n\n ",$response->getStatusCode(), $data['hydra:description']),
        );
        
    }

}


  