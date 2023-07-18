<?php

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PasswordException extends BadRequestHttpException
{
    public static function invalidLength():self
    {
        throw new self('Password must be at least 6 characters');
    }
}