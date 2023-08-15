<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\Request;

class ActivateAccountService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function activate(string $id, string $token): User
    {
        $user = $this->userRepository->findOneInactiveOrFail($id, $token);

        $user->setActive(true);
        $user->setToken(null);
        $this->userRepository->saveEntity($user);

        return $user;
    }
}
