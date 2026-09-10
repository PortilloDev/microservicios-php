<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Payment
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_REQUIRES_CONFIRMATION = 'requires_confirmation';
    public const STATUS_SUCCEEDED = 'succeeded';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';

    private string $id;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    private Collection $refunds;

    public function __construct(
        private string $orderId,
        private string $userEmail,
        private int $amount,
        private string $currency,
        private string $paymentMethod,
        private string $provider,
        private string $idempotencyKey,
        private string $status = self::STATUS_PENDING,
        private ?string $providerPaymentIntentId = null,
        private ?string $providerChargeId = null,
        private ?string $failureCode = null,
        private ?string $failureMessage = null,
    ) {
        $this->id = Uuid::v4()->toRfc4122();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->refunds = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getProviderPaymentIntentId(): ?string
    {
        return $this->providerPaymentIntentId;
    }

    public function getProviderChargeId(): ?string
    {
        return $this->providerChargeId;
    }

    public function getFailureCode(): ?string
    {
        return $this->failureCode;
    }

    public function getFailureMessage(): ?string
    {
        return $this->failureMessage;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function getRefunds(): Collection
    {
        return $this->refunds;
    }

    public function markRequiresConfirmation(string $paymentIntentId): void
    {
        $this->status = self::STATUS_REQUIRES_CONFIRMATION;
        $this->providerPaymentIntentId = $paymentIntentId;
        $this->touch();
    }

    public function markSucceeded(string $chargeId): void
    {
        $this->status = self::STATUS_SUCCEEDED;
        $this->providerChargeId = $chargeId;
        $this->failureCode = null;
        $this->failureMessage = null;
        $this->touch();
    }

    public function markFailed(string $failureCode, string $failureMessage): void
    {
        $this->status = self::STATUS_FAILED;
        $this->failureCode = $failureCode;
        $this->failureMessage = $failureMessage;
        $this->touch();
    }

    public function markRefunded(): void
    {
        $this->status = self::STATUS_REFUNDED;
        $this->touch();
    }

    public function addRefund(Refund $refund): void
    {
        if (!$this->refunds->contains($refund)) {
            $this->refunds->add($refund);
        }
    }

    public function isSucceeded(): bool
    {
        return self::STATUS_SUCCEEDED === $this->status;
    }

    private function touch(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'orderId' => $this->orderId,
            'userEmail' => $this->userEmail,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'paymentMethod' => $this->paymentMethod,
            'provider' => $this->provider,
            'status' => $this->status,
            'providerPaymentIntentId' => $this->providerPaymentIntentId,
            'providerChargeId' => $this->providerChargeId,
            'failureCode' => $this->failureCode,
            'failureMessage' => $this->failureMessage,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
