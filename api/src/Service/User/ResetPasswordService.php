<?php

namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\ORMException;
use App\Repository\UserRepository;
use App\Service\User\EncoderService;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;

class ResetPasswordService
{

    private UserRepository $userRepository;
    private EncoderService $encoderService;

    public function __construct(UserRepository $userRepository, EncoderService $encoderService)
    {
        $this->userRepository = $userRepository;
        $this->encoderService = $encoderService;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function reset(Request $request): User
    {
        $userId = RequestService::getField($request, 'userId');
        $resetPasswordToken = RequestService::getField($request, 'resetPasswordToken');
        $password = RequestService::getField($request, 'new-password');

        $user = $this->userRepository->findOneByIdAndResetPasswordToken($userId, $resetPasswordToken);
        $user->setPassword($this->encoderService->generateEncodedPassword($user, $password));
        $user->setResetPasswordToken(null);

        $this->userRepository->saveEntity($user);

        return $user;
    }
}