<?php

namespace App\Repository;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;


class UserRepository extends BaseRepository
{

    protected static function entityClass(): string
    {
        return  User::class;
    }

    public function findOneByIdOrFail(string $id): User
    {
        if (null === $user = $this->objectRepository->find($id)) {
            throw UserNotFoundException::fromId($id);
        }

        return $user;
    }

    public function findOneByEmailOrFail(string $email): User
    {
        if (null === $user = $this->objectRepository->findOneBy(['email' => $email])) {
            throw UserNotFoundException::fromEmail($email);
        }

        return $user;
    }

    public function findOneInactiveOrFail(string $id, string $token): User
    {
        if (null === $user = $this->objectRepository->findOneBy(['id' => $id, 'token' => $token, 'active' => false])) {
            throw UserNotFoundException::fromActivationToken($id, $token);
        }

        return $user;
    }

    public function findOneByIdAndResetPasswordToken(string $id, string $token): User
    {
        if (null === $user = $this->objectRepository->findOneBy(['id' => $id, 'resetPasswordToken' => $token])) {
            throw UserNotFoundException::fromResetPasswordToken($id, $token);
        }

        return $user;
    }
}
