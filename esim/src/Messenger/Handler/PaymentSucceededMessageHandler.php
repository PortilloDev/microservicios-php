<?php

namespace App\Messenger\Handler;

use App\Messenger\Message\PaymentSucceededMessage;
use App\Service\ProvisionEsimAction;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PaymentSucceededMessageHandler implements MessageHandlerInterface
{
    public function __construct(private ProvisionEsimAction $provisionEsimAction)
    {
    }

    public function __invoke(PaymentSucceededMessage $message): void
    {
        ($this->provisionEsimAction)(
            $message->orderId,
            $message->userEmail,
            $message->metadata['plan'] ?? 'default',
            $message->paymentId,
            $message->metadata
        );
    }
}
