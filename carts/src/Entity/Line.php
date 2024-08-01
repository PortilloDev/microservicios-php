<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class Line
{
    private string $id;
    public function __construct(
        private Cart $cart,
        private string $productId,
        private int $quantity,
    ) {
        $this->id = Uuid::v4()->toRfc4122();
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function getCart(): Cart
    {
        return $this->cart;
    }
    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'cartId' => $this->cart->getId(),
            'productId' => $this->productId,
            'quantity' => $this->quantity,
        ];
    }
}