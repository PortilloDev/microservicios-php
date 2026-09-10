<?php

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PaymentRepository extends ServiceEntityRepository implements PaymentRepositoryInterface
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Payment::class);
    }

    public function findById(string $id): ?Payment
    {
        return $this->find($id);
    }

    public function findByOrderId(string $orderId): ?Payment
    {
        return $this->findOneBy(['orderId' => $orderId]);
    }

    public function list(): array
    {
        return $this->findBy([], ['createdAt' => 'DESC']);
    }

    public function save(Payment $payment): void
    {
        $this->getEntityManager()->persist($payment);
        $this->getEntityManager()->flush();
    }
}
