<?php

namespace App\Repository;

use App\Entity\Inventory;
use Symfony\Component\Uid\UuidV4;

interface InventoryRepositoryInterface
{
    public function save(Inventory $inventory): void;
    public function findByProductId(UuidV4 $productId): ?Inventory;
    public function findById(UuidV4 $id): ?Inventory;
}