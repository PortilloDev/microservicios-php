<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class User
{
    private string $id;
    private \DateTime $createdAt;
    public function __construct(
        private string $name,
        private string $email,
        private string $code,
        private ?string $password = null,
    ) {
        $this->id = Uuid::v4()->toRfc4122();
        $this->createdAt = new \DateTime();
    }


    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }


    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'code' => $this->code,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }

}