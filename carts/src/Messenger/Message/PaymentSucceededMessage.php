<?php

namespace App\Messenger\Message;

class PaymentSucceededMessage
{
    public function __construct(
        public string $paymentId,
        public string $orderId,
        public string $userEmail,
        public int $amount,
        public string $currency,
        public string $providerPaymentId,
        public array $metadata = [],
    ) {
    }
}
