<?php

namespace App\Service;

use App\Entity\Cart;
use App\Repository\CartRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class FindCartByUserAction
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
    ) {
    }
    public function __invoke(string $userEmail): ?Cart
    {
       $cart = $this->cartRepository->findByUserAndOpenCart($userEmail, Cart::STATUS_OPEN);

       if (!$cart) {
          return null;
       }
       return $cart;
    }
}