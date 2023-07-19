<?php

namespace App\Messenger\Message;

class UserRegisteredMessage
{
    private $userId;
    private $name;
    private $email;
    private $token;


    public function __construct(string $userId, string $name, string $email, string $token)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
