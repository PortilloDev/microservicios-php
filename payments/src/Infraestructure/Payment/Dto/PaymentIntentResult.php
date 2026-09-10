<?php

namespace App\Infraestructure\Payment\Dto;

class PaymentIntentResult
{
    public const STATUS_REQUIRES_CONFIRMATION = 'requires_confirmation';
    public const STATUS_SUCCEEDED = 'succeeded';
    public const STATUS_FAILED = 'failed';

    public function __construct(
        public string $id,
        public string $status,
        public int $amount,
        public string $currency,
        public ?string $chargeId = null,
        public ?string $failureCode = null,
        public ?string $failureMessage = null,
    ) {
    }

    public function isSucceeded(): bool
    {
        return self::STATUS_SUCCEEDED === $this->status;
    }
}
