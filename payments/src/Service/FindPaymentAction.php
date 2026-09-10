<?php

namespace App\Service;

use App\Entity\Payment;
use App\Repository\PaymentRepositoryInterface;

class FindPaymentAction
{
    public function __construct(private PaymentRepositoryInterface $paymentRepository)
    {
    }

    public function byId(string $id): ?Payment
    {
        return $this->paymentRepository->findById($id);
    }

    public function byOrderId(string $orderId): ?Payment
    {
        return $this->paymentRepository->findByOrderId($orderId);
    }

    public function list(): array
    {
        return $this->paymentRepository->list();
    }
}
