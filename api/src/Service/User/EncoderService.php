<?php

namespace App\Service\Password;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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

    public function generateEncodedPassword(UserInterface $user, string $password)
    {
        if(self::MININUM_LENGHT > strlen($password)){

        }
    }


}