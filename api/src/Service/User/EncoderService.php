<?php

namespace App\Service\User;

use App\Entity\User;use App\Exception\User\PasswordException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;use Symfony\Component\Security\Core\User\UserInterface;

class EncoderService
{
    private UserPasswordHasherInterface $passwordHasher;
    private const MININUM_LENGHT = 6;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function generateEncodedPassword(PasswordAuthenticatedUserInterface $user, string $password): string
    {
        if(self::MININUM_LENGHT > strlen($password)){
            throw PasswordException::invalidLength();
        }

        return $this->passwordHasher->hashPassword($user, $password);
    }

    public function isPasswordValid(UserInterface $user, string $password): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $password);
    }

}