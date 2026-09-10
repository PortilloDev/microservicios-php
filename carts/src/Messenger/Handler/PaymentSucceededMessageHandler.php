<?php

namespace App\Messenger\Handler;

use App\Entity\Cart;
use App\Messenger\Message\PaymentSucceededMessage;
use App\Repository\CartRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PaymentSucceededMessageHandler implements MessageHandlerInterface
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(PaymentSucceededMessage $message): void
    {
        $cart = $this->cartRepository->findById($message->orderId);
        if (null === $cart) {
            $this->logger->error('Cart not found for succeeded payment.', ['orderId' => $message->orderId]);

            return;
        }

        $cart->setStatus(Cart::STATUS_PAID);
        $this->cartRepository->save($cart);

        $this->logger->info('Cart marked as paid.', ['orderId' => $message->orderId]);
    }
}
