<?php

namespace App\Infraestructure\Esim\Dto;

class EsimProvisionResult
{
    public const STATUS_PROVISIONED = 'provisioned';

    public function __construct(
        public string $iccid,
        public string $imsi,
        public string $msisdn,
        public string $smdpAddress,
        public string $matchingId,
        public string $activationCode,
        public string $qrCodeUrl,
        public string $profileName,
        public string $dataAllowance,
        public string $validity,
        public string $status = self::STATUS_PROVISIONED,
    ) {
    }
}
