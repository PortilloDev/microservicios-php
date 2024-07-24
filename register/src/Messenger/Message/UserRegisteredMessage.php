<?php

namespace App\Messenger\Message;
class UserRegisteredMessage
{
    public function __construct(private readonly string $name, private readonly string $email)
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

}