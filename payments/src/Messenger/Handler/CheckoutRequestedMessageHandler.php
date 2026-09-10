<?php

namespace App\Messenger\Handler;

use App\Messenger\Message\CheckoutRequestedMessage;
use App\Service\ProcessPaymentAction;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CheckoutRequestedMessageHandler implements MessageHandlerInterface
{
    public function __construct(private ProcessPaymentAction $processPaymentAction)
    {
    }

    public function __invoke(CheckoutRequestedMessage $message): void
    {
        ($this->processPaymentAction)($message);
    }
}
