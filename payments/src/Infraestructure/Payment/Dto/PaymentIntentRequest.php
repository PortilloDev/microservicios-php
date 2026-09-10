<?php

namespace App\Infraestructure\Payment\Dto;

class PaymentIntentRequest
{
    public function __construct(
        public string $orderId,
        public int $amount,
        public string $currency,
        public string $paymentMethod,
        public string $idempotencyKey,
        public array $metadata = [],
    ) {
    }
}
