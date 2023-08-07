<?php

namespace App\Api\Action\User;

use Doctrine\ORM\ORMException;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;
use App\Service\User\RequestResetPasswordService;
use Symfony\Component\HttpFoundation\JsonResponse;

class RequestResetPassword
{

    private RequestResetPasswordService $requestResetPasswordService;

    public function __construct(RequestResetPasswordService $requestResetPasswordService)
    {
        $this->requestResetPasswordService = $requestResetPasswordService;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(Request $request): JsonResponse
    {

        $this->requestResetPasswordService->send(RequestService::getField($request, 'email'));
        return new JsonResponse(['message' => 'Request reset password email sent']);
    }
}
