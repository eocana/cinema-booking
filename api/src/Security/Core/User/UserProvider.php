<?php

namespace App\Security\Core\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername(string $identifier): UserInterface
    {

        try {
            return $this->userRepository->findOneByEmailOrFail($identifier);
        } catch (UserNotFoundException $exception) {
            throw UserNotFoundException::fromEmail($identifier);
        }
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(\sprintf('Instances of "%s" are not supported', \get_class($user)));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        $user->setPassword($newHashedPassword);
        $this->userRepository->saveEntity($user);
    }
}
