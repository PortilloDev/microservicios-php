<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class Refund
{
    private string $id;
    private \DateTime $createdAt;

    public function __construct(
        private Payment $payment,
        private int $amount,
        private string $status,
        private ?string $providerRefundId = null,
    ) {
        $this->id = Uuid::v4()->toRfc4122();
        $this->createdAt = new \DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getProviderRefundId(): ?string
    {
        return $this->providerRefundId;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'paymentId' => $this->payment->getId(),
            'amount' => $this->amount,
            'status' => $this->status,
            'providerRefundId' => $this->providerRefundId,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
