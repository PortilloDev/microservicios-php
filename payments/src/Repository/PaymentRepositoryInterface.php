<?php

namespace App\Repository;

use App\Entity\Payment;

interface PaymentRepositoryInterface
{
    public function findById(string $id): ?Payment;

    public function findByOrderId(string $orderId): ?Payment;

    /**
     * @return Payment[]
     */
    public function list(): array;

    public function save(Payment $payment): void;
}
