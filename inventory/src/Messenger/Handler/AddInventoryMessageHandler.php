<?php

namespace App\Messenger\Handler;

use App\Entity\Inventory;
use App\Messenger\Message\AddInventoryMessage;
use App\Repository\InventoryRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Uid\Uuid;

class AddInventoryMessageHandler implements MessageHandlerInterface
{
    public const MIN_STOCK = 50;
    public function __construct(
        private InventoryRepositoryInterface $inventoryRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(AddInventoryMessage $command) :void
    {
        $inventory = $this->inventoryRepository->findByProductId(Uuid::fromString($command->productId));
        if ($inventory){
            $this->logger->error(sprintf('Inventory exist', $command->productId));
            return;
        }
        $inventory = new Inventory(Uuid::fromString($command->productId), self::MIN_STOCK);

        $this->inventoryRepository->save($inventory);
    }
}