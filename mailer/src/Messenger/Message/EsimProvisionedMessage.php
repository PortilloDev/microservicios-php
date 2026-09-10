<?php

namespace App\Messenger\Message;

class EsimProvisionedMessage
{
    public function __construct(
        public string $esimId,
        public string $orderId,
        public string $userEmail,
        public string $iccid,
        public string $activationCode,
        public string $qrCodeUrl,
        public string $profileName,
        public string $dataAllowance,
        public string $validity,
    ) {
    }
}
