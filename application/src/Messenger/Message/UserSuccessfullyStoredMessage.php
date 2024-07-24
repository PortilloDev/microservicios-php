<?php

namespace App\Messenger\Message;

class UserSuccessfullyStoredMessage
{
    public function __construct(private readonly string $name, private string $email, private string $code)
    {

    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}