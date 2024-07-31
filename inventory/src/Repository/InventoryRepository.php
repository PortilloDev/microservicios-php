<?php

namespace App\Repository;

use App\Entity\Inventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV4;

class InventoryRepository extends ServiceEntityRepository implements InventoryRepositoryInterface
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Inventory::class);
    }
    public function save(Inventory $inventory): void
    {
        $this->getEntityManager()->persist($inventory);
        $this->getEntityManager()->flush();
    }

    public function findByProductId(UuidV4 $productId): ?Inventory
    {
        return $this->findOneBy(['productId' => $productId->toRfc4122()]);
    }
    public function findById(UuidV4 $id): ?Inventory
    {
        return $this->findOneBy(['id' => $id->toRfc4122()]);
    }
}