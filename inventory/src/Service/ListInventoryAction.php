<?php

namespace App\Service;

use App\Entity\Inventory;
use App\Repository\InventoryRepositoryInterface;

class ListInventoryAction
{
    public function __construct(
        private InventoryRepositoryInterface $inventoryRepository,
    ) {
    }

    public function __invoke(): array
    {
        return $this->inventoryRepository->all();

    }
}