<?php

namespace App\Repository;

use App\Entity\Refund;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RefundRepository extends ServiceEntityRepository implements RefundRepositoryInterface
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Refund::class);
    }

    public function save(Refund $refund): void
    {
        $this->getEntityManager()->persist($refund);
        $this->getEntityManager()->flush();
    }
}
