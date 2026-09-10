<?php

namespace App\Entity;

use App\Infraestructure\Esim\Dto\EsimProvisionResult;
use Symfony\Component\Uid\Uuid;

class Esim
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROVISIONED = 'provisioned';
    public const STATUS_FAILED = 'failed';

    private string $id;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function __construct(
        private string $orderId,
        private string $userEmail,
        private string $provider,
        private string $status = self::STATUS_PENDING,
        private ?string $paymentId = null,
        private ?string $iccid = null,
        private ?string $imsi = null,
        private ?string $msisdn = null,
        private ?string $smdpAddress = null,
        private ?string $matchingId = null,
        private ?string $activationCode = null,
        private ?string $qrCodeUrl = null,
        private ?string $profileName = null,
        private ?string $dataAllowance = null,
        private ?string $validity = null,
        private ?string $failureMessage = null,
    ) {
        $this->id = Uuid::v4()->toRfc4122();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    public function getIccid(): ?string
    {
        return $this->iccid;
    }

    public function getImsi(): ?string
    {
        return $this->imsi;
    }

    public function getMsisdn(): ?string
    {
        return $this->msisdn;
    }

    public function getSmdpAddress(): ?string
    {
        return $this->smdpAddress;
    }

    public function getMatchingId(): ?string
    {
        return $this->matchingId;
    }

    public function getActivationCode(): ?string
    {
        return $this->activationCode;
    }

    public function getQrCodeUrl(): ?string
    {
        return $this->qrCodeUrl;
    }

    public function getProfileName(): ?string
    {
        return $this->profileName;
    }

    public function getDataAllowance(): ?string
    {
        return $this->dataAllowance;
    }

    public function getValidity(): ?string
    {
        return $this->validity;
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

    public function isProvisioned(): bool
    {
        return self::STATUS_PROVISIONED === $this->status;
    }

    public function markProvisioned(EsimProvisionResult $result, ?string $paymentId = null): void
    {
        $this->status = self::STATUS_PROVISIONED;
        $this->paymentId = $paymentId ?? $this->paymentId;
        $this->iccid = $result->iccid;
        $this->imsi = $result->imsi;
        $this->msisdn = $result->msisdn;
        $this->smdpAddress = $result->smdpAddress;
        $this->matchingId = $result->matchingId;
        $this->activationCode = $result->activationCode;
        $this->qrCodeUrl = $result->qrCodeUrl;
        $this->profileName = $result->profileName;
        $this->dataAllowance = $result->dataAllowance;
        $this->validity = $result->validity;
        $this->failureMessage = null;
        $this->touch();
    }

    public function markFailed(string $failureMessage): void
    {
        $this->status = self::STATUS_FAILED;
        $this->failureMessage = $failureMessage;
        $this->touch();
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
            'provider' => $this->provider,
            'status' => $this->status,
            'paymentId' => $this->paymentId,
            'iccid' => $this->iccid,
            'imsi' => $this->imsi,
            'msisdn' => $this->msisdn,
            'smdpAddress' => $this->smdpAddress,
            'matchingId' => $this->matchingId,
            'activationCode' => $this->activationCode,
            'qrCodeUrl' => $this->qrCodeUrl,
            'profileName' => $this->profileName,
            'dataAllowance' => $this->dataAllowance,
            'validity' => $this->validity,
            'failureMessage' => $this->failureMessage,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
