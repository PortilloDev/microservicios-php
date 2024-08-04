<?php

namespace App\Repository;

use App\Entity\Inventory;
use Symfony\Component\Uid\UuidV4;

interface InventoryRepositoryInterface
{
    public function save(Inventory $inventory): void;
    public function findByProductId(string $productId): ?Inventory;
    public function findById(string $id): ?Inventory;

    public function all(): array;
}