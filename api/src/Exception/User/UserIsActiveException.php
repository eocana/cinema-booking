<?php

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserIsActiveException extends ConflictHttpException
{
    private const MESSAGE = 'User with email "%s" is already active';

    public static function fromEmail(string $email): self
    {
        return new self(\sprintf(self::MESSAGE, $email));
    }
}
