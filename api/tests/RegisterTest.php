<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterTest extends WebTestCase
{   


     public function testRegistrationEndpoint()
    {
        $client = static::createClient();

        // Execute the POST request to the register endpoint
         $client->request(
             'POST',
             '/api/v1/users/register',
             [],
             [],
             ['CONTENT_TYPE' => 'application/json'],
         );


        // Check if the status code return is 200 (OK)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    // public function testRegistration()
    // {
    //     $client = static::createClient();

    //     // Define los datos del nuevo usuario
    //     $randomNum = rand(0, 10000); // generamos un número aleatorio entre 0 y 10000

    //     $payload = [
    //         'name' => 'test_' . $randomNum, // añadimos el número aleatorio al nombre
    //         'lastname' => 'testeo_' . $randomNum, // añadimos el número aleatorio al apellido
    //         'email' => 'test_' . $randomNum . '@example.com', // añadimos el número aleatorio al correo electrónico
    //         'password' => '1234',
    //         'username' => 'test_' . $randomNum // añadimos el número aleatorio al nombre de usuario
    //     ];

    //     // Realiza la petición de registro
    //     $client->request(
    //         'POST',
    //         '/api/v1/users/register',
    //         [],
    //         [],
    //         ['CONTENT_TYPE' => 'application/json'],
    //         $payload
    //     );

    //     // Comprueba que la respuesta es éxito (status 200)
    //     $this->assertEquals(200, $client->getResponse()->getStatusCode());

    //     // Comprueba que el usuario se creó correctamente
    //     // Estos chequeos pueden variar dependiendo de tu unidad de trabajo y cómo estás manejando los usuarios
    //     // Por ejemplo, puedes ir a la base de datos y confirmar que el usuario se creó correctamente con sus datos
    // }
}