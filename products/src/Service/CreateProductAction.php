<?php

namespace App\Service;

use App\Entity\Product;
use App\Messenger\Message\AddInventoryMessage;
use App\Messenger\RoutingKey;
use App\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateProductAction
{
    public function __construct(private ProductRepositoryInterface $productRepository, private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(string $name, float $price, ?string $description): ?Product
    {
        $product = new Product($name, $price, $description);
        $this->productRepository->save($product);

        $this->messageBus->dispatch(
            new AddInventoryMessage($product->getId()),
            [new AmqpStamp(RoutingKey::ADD_INVENTORY_QUEUE)]
        );
        return $product;
    }

}