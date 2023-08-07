<?php

namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\ORMException;
use App\Repository\UserRepository;
use App\Service\User\EncoderService;
use App\Service\Request\RequestService;
use App\Exception\User\PasswordException;
use App\Exception\User\UserNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordService
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
    public function changePassword(string $userId, string $oldPassword, string $newPassword): User
    {

        $user = $this->userRepository->findOneByIdOrFail($userId);

        if (!$this->encoderService->isPasswordValid($user, $oldPassword)) {
            throw PasswordException::oldPasswordNotValid();
        }

        $user->setPassword($this->encoderService->generateEncodedPassword($user, $newPassword));

        $this->userRepository->saveEntity($user);

        return $user;
    }
}
