<?php

namespace App\Service;

use App\Entity\Esim;
use App\Repository\EsimRepositoryInterface;

class FindEsimAction
{
    public function __construct(private EsimRepositoryInterface $esimRepository)
    {
    }

    public function byId(string $id): ?Esim
    {
        return $this->esimRepository->findById($id);
    }

    public function byOrderId(string $orderId): ?Esim
    {
        return $this->esimRepository->findByOrderId($orderId);
    }

    public function list(): array
    {
        return $this->esimRepository->list();
    }
}
