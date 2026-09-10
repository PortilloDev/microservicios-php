<?php

namespace App\Repository;

use App\Entity\Esim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EsimRepository extends ServiceEntityRepository implements EsimRepositoryInterface
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Esim::class);
    }

    public function findById(string $id): ?Esim
    {
        return $this->find($id);
    }

    public function findByOrderId(string $orderId): ?Esim
    {
        return $this->findOneBy(['orderId' => $orderId]);
    }

    public function list(): array
    {
        return $this->findBy([], ['createdAt' => 'DESC']);
    }

    public function save(Esim $esim): void
    {
        $this->getEntityManager()->persist($esim);
        $this->getEntityManager()->flush();
    }
}
