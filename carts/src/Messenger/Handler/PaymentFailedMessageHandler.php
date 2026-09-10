<?php

namespace App\Messenger\Handler;

use App\Entity\Cart;
use App\Messenger\Message\PaymentFailedMessage;
use App\Repository\CartRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PaymentFailedMessageHandler implements MessageHandlerInterface
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(PaymentFailedMessage $message): void
    {
        $cart = $this->cartRepository->findById($message->orderId);
        if (null === $cart) {
            $this->logger->error('Cart not found for failed payment.', ['orderId' => $message->orderId]);

            return;
        }

        $cart->setStatus(Cart::STATUS_PAYMENT_FAILED);
        $this->cartRepository->save($cart);

        $this->logger->warning('Cart marked as payment failed.', [
            'orderId' => $message->orderId,
            'failureCode' => $message->failureCode,
        ]);
    }
}
