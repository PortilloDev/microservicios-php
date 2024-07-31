<?php

namespace App\Service;

use App\Entity\Inventory;
use App\Repository\InventoryRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class UpdateProductInInventoryAction
{
    public function __construct(
        private InventoryRepositoryInterface $inventoryRepository,
        private FindProductInServiceAction $findProductInServiceAction,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(string $productId, int $quantity) :void
    {
       $existProduct = $this->findProductInServiceAction->__invoke($productId);
       if ($existProduct === null){
           throw new \Exception('Product not found', 404);
       }
        $inventory = $this->inventoryRepository->findByProductId(Uuid::fromString($productId));

        if ($inventory) {
            $this->messageBus->dispatch(new UpdateInventoryAction($inventory->getId(), $quantity));
        } else {
            $quantity = max($quantity, 0);
            $inventory = new Inventory($productId, $quantity);
        }
        $this->inventoryRepository->save($inventory);
    }
}