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
        private GetStockOfTheProductAction $getStockOfTheProductAction,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(string $productId, string $userEmail, int $quantity) :string
    {
       $product = $this->findProductInServiceAction->__invoke($productId);
       if (!$product){
           throw new \Exception('Product not found', 404);
       }
       $stock = $this->getStockOfTheProductAction->__invoke($productId);
        if (!$stock){
            throw new \Exception('Product not found', 404);
        }
        if(isset($stock['quantity']) && $stock['quantity'] < $quantity){
            throw new \Exception('Not enough stock', 400);
        }

        try{
            $this->findUserServiceAction->__invoke($userEmail);
        }catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(),
                $exception->getCode() === 404 ? 404 : 500);
        }

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