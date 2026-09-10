<?php

namespace App\Infraestructure\Esim;

use App\Infraestructure\Esim\Dto\EsimProvisionRequest;
use App\Infraestructure\Esim\Dto\EsimProvisionResult;

interface EsimProviderInterface
{
    public function getName(): string;

    public function provision(EsimProvisionRequest $request): EsimProvisionResult;
}
