<?php

namespace App\Service;

use App\Entity\Cart;
use App\Repository\CartRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CloseCartAction
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
    ) {
    }

    public function __invoke(string $cartId): void
    {
        $cart = $this->cartRepository->findById($cartId);
        if (!$cart) {
            throw new \Exception('Cart not found', 404);
        }
        $cart->setStatus(Cart::STATUS_ORDERED);
        $this->cartRepository->save($cart);
    }
}