<?php

namespace App\Infraestructure\Payment\Dto;

class RefundResult
{
    public function __construct(
        public string $id,
        public string $status,
        public int $amount,
        public string $paymentIntentId,
    ) {
    }
}
