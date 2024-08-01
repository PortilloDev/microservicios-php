<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Line;
use App\Messenger\Message\UpdateInventoryMessage;
use App\Messenger\RoutingKey;
use App\Repository\CartRepositoryInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class AddToCartAction
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private FindProductInServiceAction $findProductInServiceAction,
        private FindUserServiceAction $findUserServiceAction,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(string $productId, string $userEmail, int $quantity) :string
    {
       $existProduct = $this->findProductInServiceAction->__invoke($productId);
       if ($existProduct === null){
           throw new \Exception('Product not found', 404);
       }
        $this->findUserServiceAction->__invoke($userEmail);
        $cart = $this->cartRepository->findByUserAndOpenCart($userEmail, Cart::STATUS_OPEN);

        if($cart) {
            $line = new Line($cart, $productId, $quantity);
            $cart->addLine($line);
        } else {
            $cart = new Cart($userEmail);
            $line = new Line($cart, $productId, $quantity);
            $cart->addLine($line);
        }
        $this->cartRepository->save($cart);
        $this->messageBus->dispatch(
            new UpdateInventoryMessage($productId, $quantity),
            [new AmqpStamp(RoutingKey::UPDATE_INVENTORY_QUEUE)]
        );
        return $cart->getId();
    }
}