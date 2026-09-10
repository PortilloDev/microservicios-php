<?php

namespace App\Service;

use App\Entity\Refund;
use App\Infraestructure\Payment\PaymentProviderInterface;
use App\Repository\PaymentRepositoryInterface;
use App\Repository\RefundRepositoryInterface;

class RefundPaymentAction
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository,
        private RefundRepositoryInterface $refundRepository,
        private PaymentProviderInterface $paymentProvider,
    ) {
    }

    public function __invoke(string $paymentId, ?int $amount = null): Refund
    {
        $payment = $this->paymentRepository->findById($paymentId);
        if (null === $payment) {
            throw new \Exception('Payment not found', 404);
        }

        if (!$payment->isSucceeded()) {
            throw new \Exception('Only a succeeded payment can be refunded', 409);
        }

        if (null === $payment->getProviderPaymentIntentId()) {
            throw new \Exception('Payment has no provider reference', 409);
        }

        $result = $this->paymentProvider->refundPaymentIntent($payment->getProviderPaymentIntentId(), $amount);

        $refund = new Refund($payment, $result->amount, $result->status, $result->id);
        $payment->addRefund($refund);
        $payment->markRefunded();

        $this->refundRepository->save($refund);
        $this->paymentRepository->save($payment);

        return $refund;
    }
}
