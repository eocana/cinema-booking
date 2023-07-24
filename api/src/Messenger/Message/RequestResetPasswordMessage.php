<?php

namespace App\Messenger\Message;

class RequestResetPasswordMessage
{
    private string $email;
    private string $id;
    private string $resetPasswordToken;

    public function __construct(string $id, string $email, string $resetPasswordToken)
    {
        $this->id = $id;
        $this->email = $email;
        $this->resetPasswordToken = $resetPasswordToken;
    }


    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of resetPasswordToken
     */
    public function getResetPasswordToken()
    {
        return $this->resetPasswordToken;
    }
}
