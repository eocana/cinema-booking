<?php

namespace App\Api\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class JsonExceptionResponseTransformerListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        //TODO: revisar de que me pille bien todo el resto de exceptiones no solo la logicas o http
        $exception = $event->getThrowable();

        $responseCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;

        $message = $exception->getMessage();

        $decodedMessage = json_decode($message, true);

        if ($decodedMessage !== null && isset($decodedMessage['hydra:description'])) {
            $message = $decodedMessage['hydra:description'];
        }

        $data = [
            'class' => get_class($exception),
            'code' => $responseCode,
            'error_message' => $message,
        ];

        $event->setResponse($this->prepareResponse($data, $data['code']));
    }

    private function prepareResponse(array $data, int $statusCode): JsonResponse
    {
        $response = new JsonResponse($data, $statusCode);
        $response->headers->set('Server-Time', time());
        $response->headers->set('X-Error-Code', $statusCode);

        return  $response;
    }
}


// OLD CODE

 
/*   if ($exception instanceof  HttpExceptionInterface) {
    $data = [
        //TODO: add if for dev and prod, class only in dev mode
        'class' => get_class($exception),
        'code' => $exception->getStatusCode(),
        'message' => $exception->getMessage(),
    ];

    $event->setResponse($this->prepareResponse($data, $data['code']));
} elseif ($exception instanceof \Exception) {

    $err_ex = json_decode($exception->getMessage(), true);

    if ($err_ex !== null && isset($err_ex['hydra:description'])) {
        $data = [
            'class' => get_class($exception),
            'code' => 400, // You can assign a default code or another suitable one
            'message' => $err_ex['hydra:description'],
        ];
        $event->setResponse($this->prepareResponse($data, $data['code']));
    }
} */