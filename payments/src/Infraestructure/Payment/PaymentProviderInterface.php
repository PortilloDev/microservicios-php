<?php

namespace App\Infraestructure\Payment;

use App\Infraestructure\Payment\Dto\PaymentIntentRequest;
use App\Infraestructure\Payment\Dto\PaymentIntentResult;
use App\Infraestructure\Payment\Dto\RefundResult;

interface PaymentProviderInterface
{
    public function getName(): string;

    public function createPaymentIntent(PaymentIntentRequest $request): PaymentIntentResult;

    public function confirmPaymentIntent(string $paymentIntentId): PaymentIntentResult;

    public function refundPaymentIntent(string $paymentIntentId, ?int $amount = null): RefundResult;
}
