<?php

namespace App\Api\Action\User;

use PHPUnit\Util\Json;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\Request;
use App\Service\User\ResendActivationEmailService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResendActivationEmail
{
    private ResendActivationEmailService $resendActivationEmail;

    public function __construct(ResendActivationEmailService $resendActivationEmail)
    {
        $this->resendActivationEmail = $resendActivationEmail;
    }



    public function __invoke(Request $request): JsonResponse
    {
        $this->resendActivationEmail->resend(RequestService::getField($request, 'email'));

        return new JsonResponse(['message' => 'Activation email sent']);
    }
}
