<?php

namespace App\Messenger\Message;

class PaymentFailedMessage
{
    public function __construct(
        public string $orderId,
        public string $userEmail,
        public string $failureCode,
        public string $failureMessage,
        public ?string $paymentId = null,
    ) {
    }
}
