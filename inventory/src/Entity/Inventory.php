<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class Inventory
{

    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    private string $id;
    public function __construct(
        private string $productId,
        private int $quantity,

    ) {
        $this->id = Uuid::v4()->toRfc4122();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }


    public function getId(): string
    {
        return $this->id;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }


    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'productId' => $this->productId,
            'quantity' => $this->quantity,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

}