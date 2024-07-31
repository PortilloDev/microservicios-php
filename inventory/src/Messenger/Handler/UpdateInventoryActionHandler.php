<?php

namespace App\Messenger\Handler;

use App\Repository\InventoryRepositoryInterface;
use App\Service\UpdateInventoryAction;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Uid\Uuid;

class UpdateInventoryActionHandler implements MessageHandlerInterface
{
    public function __construct(private InventoryRepositoryInterface $inventoryRepository)
    {
    }

    public function __invoke(UpdateInventoryAction $command) :void
    {
        $inventory = $this->inventoryRepository->findById(Uuid::fromString($command->inventoryId));
        if ($inventory === null){
            return;
        }
        $total = $inventory->getQuantity() + $command->quantity;
        if ($total < 0){
            $inventory->setQuantity(0);
        }  else {
            $inventory->setQuantity($total);
        }
        $inventory->setUpdatedAt(new \DateTime());
        $this->inventoryRepository->save($inventory);
    }
}