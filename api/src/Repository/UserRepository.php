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

    public function findOneByEmailOrFail(string $email): User
    {
        if (null === $user = $this->objectRepository->findOneBy(['email'=>$email])){
            throw UserNotFoundException::fromEmail($email);
        }

        return $user;
    }
    

}
