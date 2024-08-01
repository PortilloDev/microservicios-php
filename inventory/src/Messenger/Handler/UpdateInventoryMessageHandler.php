<?php

namespace App\Messenger\Handler;

use App\Messenger\Message\NotificationStockOutMessage;
use App\Messenger\Message\UpdateInventoryMessage;
use App\Messenger\RoutingKey;
use App\Repository\InventoryRepositoryInterface;
use App\Service\UpdateInventoryAction;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class UpdateInventoryMessageHandler implements MessageHandlerInterface
{
    public function __construct(
        private InventoryRepositoryInterface $inventoryRepository,
        private MessageBusInterface $bus,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(UpdateInventoryMessage $command) :void
    {
        $inventory = $this->inventoryRepository->findByProductId(Uuid::fromString($command->productId));
        if (!$inventory){
            $this->logger->error(sprintf('Inventory with id %s not found', $command->productId));
            return;
        }
        $total = $inventory->getQuantity() - $command->quantity;
        if ($total < 0){
            $inventory->setQuantity(0);
            try {
                $this->bus->dispatch(
                    new NotificationStockOutMessage($inventory->getProductId(), $inventory->getQuantity()),
                    [new AmqpStamp(RoutingKey::MAILER_STOCK_OUT_QUEUE)]
                );
            } catch (\Exception $e) {
                throw new UnrecoverableMessageHandlingException($e->getMessage());
            }
        }  else {
            $inventory->setQuantity($total);
        }
        $inventory->setUpdatedAt(new \DateTime());

        $this->inventoryRepository->save($inventory);
    }
}