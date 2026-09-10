<?php

namespace App\Service;

use App\Entity\Payment;
use App\Infraestructure\Payment\Dto\PaymentIntentRequest;
use App\Infraestructure\Payment\PaymentProviderInterface;
use App\Infraestructure\Redis\DistributedLock;
use App\Infraestructure\Redis\IdempotencyStore;
use App\Messenger\Message\CheckoutRequestedMessage;
use App\Messenger\Message\PaymentFailedMessage;
use App\Messenger\Message\PaymentSucceededMessage;
use App\Messenger\RoutingKey;
use App\Repository\PaymentRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class ProcessPaymentAction
{
    private const LOCK_TTL_MILLISECONDS = 15000;
    private const STATUS_CACHE_TTL = 3600;

    public function __construct(
        private PaymentRepositoryInterface $paymentRepository,
        private PaymentProviderInterface $paymentProvider,
        private DistributedLock $distributedLock,
        private IdempotencyStore $idempotencyStore,
        private MessageBusInterface $messageBus,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(CheckoutRequestedMessage $message): void
    {
        $lockKey = sprintf('lock:order:%s', $message->orderId);
        $lockToken = $this->distributedLock->acquire($lockKey, self::LOCK_TTL_MILLISECONDS);

        if (null === $lockToken) {
            throw new \RuntimeException(sprintf('Payment for order %s is already being processed.', $message->orderId));
        }

        try {
            $payment = $this->paymentRepository->findByOrderId($message->orderId);

            if ($payment && $payment->isSucceeded()) {
                $this->logger->info('Payment already succeeded, skipping reprocessing.', [
                    'orderId' => $message->orderId,
                ]);

                return;
            }

            if (null === $payment) {
                $payment = new Payment(
                    orderId: $message->orderId,
                    userEmail: $message->userEmail,
                    amount: $message->amount,
                    currency: $message->currency,
                    paymentMethod: $message->paymentMethod,
                    provider: $this->paymentProvider->getName(),
                    idempotencyKey: sprintf('checkout:%s', $message->orderId),
                );
                $this->paymentRepository->save($payment);
            }

            $intent = $this->paymentProvider->createPaymentIntent(new PaymentIntentRequest(
                orderId: $message->orderId,
                amount: $message->amount,
                currency: $message->currency,
                paymentMethod: $message->paymentMethod,
                idempotencyKey: $payment->getIdempotencyKey(),
                metadata: $message->metadata,
            ));

            $payment->markRequiresConfirmation($intent->id);
            $this->paymentRepository->save($payment);

            $result = $this->paymentProvider->confirmPaymentIntent($intent->id);

            if ($result->isSucceeded()) {
                $payment->markSucceeded($result->chargeId ?? '');
                $this->paymentRepository->save($payment);

                $this->messageBus->dispatch(
                    new PaymentSucceededMessage(
                        $payment->getId(),
                        $payment->getOrderId(),
                        $payment->getUserEmail(),
                        $payment->getAmount(),
                        $payment->getCurrency(),
                        $payment->getProviderPaymentIntentId() ?? '',
                        $message->metadata,
                    ),
                    [new AmqpStamp(RoutingKey::PAYMENT_SUCCEEDED)]
                );

                $this->logger->info('Payment succeeded.', ['orderId' => $payment->getOrderId()]);
            } else {
                $payment->markFailed($result->failureCode ?? 'unknown', $result->failureMessage ?? '');
                $this->paymentRepository->save($payment);

                $this->messageBus->dispatch(
                    new PaymentFailedMessage(
                        $payment->getOrderId(),
                        $payment->getUserEmail(),
                        $result->failureCode ?? 'unknown',
                        $result->failureMessage ?? '',
                        $payment->getId(),
                    ),
                    [new AmqpStamp(RoutingKey::PAYMENT_FAILED)]
                );

                $this->logger->warning('Payment failed.', [
                    'orderId' => $payment->getOrderId(),
                    'failureCode' => $result->failureCode,
                ]);
            }

            $this->idempotencyStore->remember(
                sprintf('payment:status:%s', $payment->getOrderId()),
                $payment->getStatus(),
                self::STATUS_CACHE_TTL
            );
        } finally {
            $this->distributedLock->release($lockKey, $lockToken);
        }
    }
}
