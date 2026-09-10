<?php

namespace App\Service;

use App\Entity\Esim;
use App\Infraestructure\Esim\Dto\EsimProvisionRequest;
use App\Infraestructure\Esim\EsimProviderInterface;
use App\Infraestructure\Redis\DistributedLock;
use App\Infraestructure\Redis\IdempotencyStore;
use App\Messenger\Message\EsimProvisionedMessage;
use App\Messenger\RoutingKey;
use App\Repository\EsimRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class ProvisionEsimAction
{
    private const LOCK_TTL_MILLISECONDS = 15000;
    private const STATUS_CACHE_TTL = 3600;

    public function __construct(
        private EsimRepositoryInterface $esimRepository,
        private EsimProviderInterface $esimProvider,
        private DistributedLock $distributedLock,
        private IdempotencyStore $idempotencyStore,
        private MessageBusInterface $messageBus,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(
        string $orderId,
        string $userEmail,
        string $plan = 'default',
        ?string $paymentId = null,
        array $metadata = []
    ): Esim {
        $lockKey = sprintf('lock:order:%s', $orderId);
        $lockToken = $this->distributedLock->acquire($lockKey, self::LOCK_TTL_MILLISECONDS);

        if (null === $lockToken) {
            throw new \RuntimeException(sprintf('eSIM for order %s is already being provisioned.', $orderId));
        }

        try {
            $esim = $this->esimRepository->findByOrderId($orderId);

            if ($esim && $esim->isProvisioned()) {
                $this->logger->info('eSIM already provisioned, skipping.', ['orderId' => $orderId]);

                return $esim;
            }

            if (null === $esim) {
                $esim = new Esim($orderId, $userEmail, $this->esimProvider->getName(), Esim::STATUS_PENDING, $paymentId);
                $this->esimRepository->save($esim);
            }

            try {
                $result = $this->esimProvider->provision(new EsimProvisionRequest(
                    orderId: $orderId,
                    userEmail: $userEmail,
                    plan: $metadata['plan'] ?? $plan,
                    metadata: $metadata,
                ));
            } catch (\Throwable $exception) {
                $esim->markFailed($exception->getMessage());
                $this->esimRepository->save($esim);

                throw $exception;
            }

            $esim->markProvisioned($result, $paymentId);
            $this->esimRepository->save($esim);

            $this->messageBus->dispatch(
                new EsimProvisionedMessage(
                    $esim->getId(),
                    $esim->getOrderId(),
                    $esim->getUserEmail(),
                    $esim->getIccid() ?? '',
                    $esim->getActivationCode() ?? '',
                    $esim->getQrCodeUrl() ?? '',
                    $esim->getProfileName() ?? '',
                    $esim->getDataAllowance() ?? '',
                    $esim->getValidity() ?? '',
                ),
                [new AmqpStamp(RoutingKey::ESIM_PROVISIONED)]
            );

            $this->idempotencyStore->remember(
                sprintf('esim:status:%s', $orderId),
                $esim->getStatus(),
                self::STATUS_CACHE_TTL
            );

            $this->logger->info('eSIM provisioned.', [
                'orderId' => $orderId,
                'iccid' => $esim->getIccid(),
            ]);

            return $esim;
        } finally {
            $this->distributedLock->release($lockKey, $lockToken);
        }
    }
}
