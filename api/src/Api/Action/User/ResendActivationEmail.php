<?php

namespace App\Api\Action\User;

use App\Service\User\ResendActivationEmailService;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\Request;
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
        $this->resendActivationEmail->resend($request);

        return new JsonResponse(['message' => 'Activation email sent']);
    }
}
