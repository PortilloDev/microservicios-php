<?php

namespace App\Infraestructure\Esim\Dto;

class EsimProvisionRequest
{
    public function __construct(
        public string $orderId,
        public string $userEmail,
        public string $plan = 'default',
        public array $metadata = [],
    ) {
    }
}
