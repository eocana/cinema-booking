<?php

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends  NotFoundHttpException
{
    private const MESSAGE_NO_EMAIL = 'User with email %s not found';
    private const MESSAGE_ACTIVATION = 'User with id %s and activation token %s not found';
    private const MESSAGE_RESET_PASSWORD = 'User with id %s and reset password token %s not found';

    public static function  fromEmail(string $email): self
    {
        throw new self(\sprintf(self::MESSAGE_NO_EMAIL, $email));
    }

    public static function fromActivationToken(string $id, string $token): self
    {
        throw new self(\sprintf(self::MESSAGE_ACTIVATION, $id, $token));
    }

    public static function fromResetPasswordToken(string $id, string $token): self
    {
        throw new self(\sprintf(self::MESSAGE_RESET_PASSWORD, $id, $token));
    }
}
