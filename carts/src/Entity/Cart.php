<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Cart
{
    public const STATUS_OPEN = 'open';
    public const STATUS_ORDERED = 'ordered';

    private string $id;
    private \DateTime $createdAt;

    private Collection $lines;

    public function __construct(
        private string $userEmail,
        private string $status = self::STATUS_OPEN,
    ) {
        $this->id = Uuid::v4()->toRfc4122();
        $this->createdAt = new \DateTime();
        $this->lines = new ArrayCollection();
    }


    public function getId(): string
    {
        return $this->id;
    }


    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


    public function getUserEmail(): string
    {
        return $this->userEmail;
    }


    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getLines(): Collection
    {
        return $this->lines;
    }
    public function addLine(Line $line): void
    {
       if( !$this->lines->contains($line)) {
           $this->lines->add($line);
       }
    }

    public function removeLine(Line $line): void
    {
        if( $this->lines->contains($line)) {
            $this->lines->removeElement($line);
        }
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'user_email' => $this->userEmail,
            'status' => $this->status,
            'lines' => $this->lines->map(fn(Line $line) => $line->toArray())->toArray()
        ];
    }

}