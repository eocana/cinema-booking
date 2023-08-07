<?php

namespace App\Api\Action\User;

use App\Entity\User;
use Doctrine\ORM\ORMException;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use App\Service\User\ChangePasswordService;
use Symfony\Component\HttpFoundation\Request;

class ChangePassword
{

    private ChangePasswordService $changePasswordService;

    public function __construct(ChangePasswordService $changePasswordService)
    {
        $this->changePasswordService = $changePasswordService;
    }


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(Request $request, User $user): User
    {
        return $this->changePasswordService->changePassword(
            $user->getId(),
            RequestService::getField($request, 'oldPassword'),
            RequestService::getField($request, 'newPassword')
        );
    }
}
