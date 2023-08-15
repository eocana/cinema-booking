<?php

namespace App\Api\Action\User;

use App\Entity\User;
use Doctrine\ORM\ORMException;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use App\Service\User\ResetPasswordService;
use Symfony\Component\HttpFoundation\Request;

class ResetPassword
{

    private ResetPasswordService $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(Request $request): User
    {
        $userId = RequestService::getField($request, 'userId');
        $resetPasswordToken = RequestService::getField($request, 'resetPasswordToken');
        $password = RequestService::getField($request, 'new-password');

        return $this->resetPasswordService->reset($userId, $resetPasswordToken, $password);
    }
}
