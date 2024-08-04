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

    public function findByProductId(string $productId): ?Inventory
    {
        return $this->findOneBy(['productId' => $productId]);
    }
    public function findById(string $id): ?Inventory
    {
        return $this->findOneBy(['id' => $id]);
    }
    public function all(): array
    {
        return $this->findAll();
    }
}