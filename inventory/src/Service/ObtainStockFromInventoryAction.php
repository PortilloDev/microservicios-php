<?php

namespace App\Service;

use App\Entity\Inventory;
use App\Repository\InventoryRepositoryInterface;

class ObtainStockFromInventoryAction
{
    public function __construct(
        private InventoryRepositoryInterface $inventoryRepository,
    ) {
    }

    public function __invoke(string $productId): ?Inventory
    {
        $inventory = $this->inventoryRepository->findByProductId($productId);
        if ($inventory === null) {
            return null;
        }
       return $inventory;
    }
}