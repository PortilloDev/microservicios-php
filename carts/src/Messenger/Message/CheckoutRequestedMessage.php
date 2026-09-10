<?php

namespace App\Messenger\Message;

class CheckoutRequestedMessage
{
    public function __construct(
        public string $orderId,
        public string $userEmail,
        public int $amount,
        public string $currency = 'EUR',
        public string $paymentMethod = 'tok_visa',
        public array $metadata = [],
    ) {
    }
}
