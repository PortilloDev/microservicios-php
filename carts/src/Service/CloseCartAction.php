<?php

namespace App\Service;

use App\Entity\Cart;
use App\Messenger\Message\CheckoutRequestedMessage;
use App\Messenger\RoutingKey;
use App\Repository\CartRepositoryInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class CloseCartAction
{
    private const DEFAULT_CURRENCY = 'EUR';
    private const DEFAULT_PAYMENT_METHOD = 'tok_visa';

    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private FindProductInServiceAction $findProductInServiceAction,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(string $cartId): Cart
    {
        $cart = $this->cartRepository->findById($cartId);
        if (!$cart) {
            throw new \Exception('Cart not found', 404);
        }

        if (Cart::STATUS_OPEN !== $cart->getStatus()) {
            throw new \Exception('Cart is not open', 409);
        }

        $amount = $this->calculateAmount($cart);

        $cart->setStatus(Cart::STATUS_CHECKOUT_PENDING);
        $this->cartRepository->save($cart);

        $this->messageBus->dispatch(
            new CheckoutRequestedMessage(
                $cart->getId(),
                $cart->getUserEmail(),
                $amount,
                self::DEFAULT_CURRENCY,
                self::DEFAULT_PAYMENT_METHOD,
                ['plan' => 'default'],
            ),
            [new AmqpStamp(RoutingKey::CHECKOUT_PAYMENT_QUEUE)]
        );

        return $cart;
    }

    private function calculateAmount(Cart $cart): int
    {
        $amount = 0;

        foreach ($cart->getLines() as $line) {
            $product = $this->findProductInServiceAction->__invoke($line->getProductId());
            if (!$product || !isset($product['price'])) {
                throw new \Exception(sprintf('Product %s not found', $line->getProductId()), 404);
            }

            $amount += (int) round(((float) $product['price']) * 100) * $line->getQuantity();
        }

        if ($amount <= 0) {
            throw new \Exception('Cannot checkout an empty cart', 400);
        }

        return $amount;
    }
}
