<?php

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;


class UserAlreadyExistException extends ConflictHttpException
{
    private const MESSAGE = 'User with email "%s" already exist';
    private const MESSAGE_SQL = 'Error SQL: %s';

    public static function fromEmail(string $email): self
    {
        throw new self(\sprintf(self::MESSAGE, $email));
    }

    public static function fromSQL(string $sql_error): self
    {
        throw new self(\sprintf(self::MESSAGE_SQL, $sql_error));
    }
}
