<?php

namespace App\Repository;

use App\Entity\Esim;

interface EsimRepositoryInterface
{
    public function findById(string $id): ?Esim;

    public function findByOrderId(string $orderId): ?Esim;

    /**
     * @return Esim[]
     */
    public function list(): array;

    public function save(Esim $esim): void;
}
