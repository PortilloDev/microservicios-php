<?php

namespace App\Infraestructure\Payment;

use App\Infraestructure\Payment\Dto\PaymentIntentRequest;
use App\Infraestructure\Payment\Dto\PaymentIntentResult;
use App\Infraestructure\Payment\Dto\RefundResult;
use App\Infraestructure\Redis\IdempotencyStore;

/**
 * Simulated PSP that mimics the Stripe PaymentIntent lifecycle:
 *
 *   createPaymentIntent()  -> pi_xxx, status "requires_confirmation"
 *   confirmPaymentIntent() -> ch_xxx, status "succeeded" | "failed"
 *   refundPaymentIntent()  -> re_xxx, status "succeeded"
 *
 * Test payment methods (like Stripe test tokens):
 *   tok_visa, tok_mastercard, tok_amex  -> succeeded
 *   tok_chargeDeclined                  -> card_declined
 *   tok_insufficientFunds               -> insufficient_funds
 *   tok_expiredCard                     -> expired_card
 */
class FakeStripePaymentProvider implements PaymentProviderInterface
{
    private const PROVIDER_NAME = 'fake_stripe';
    private const INTENT_TTL = 86400;
    private const IDEMPOTENCY_TTL = 86400;

    private const SUCCESSFUL_TOKENS = ['tok_visa', 'tok_mastercard', 'tok_amex'];

    private const DECLINED_TOKENS = [
        'tok_chargeDeclined' => ['card_declined', 'Your card was declined.'],
        'tok_insufficientFunds' => ['insufficient_funds', 'Your card has insufficient funds.'],
        'tok_expiredCard' => ['expired_card', 'Your card has expired.'],
    ];

    public function __construct(private IdempotencyStore $idempotencyStore)
    {
    }

    public function getName(): string
    {
        return self::PROVIDER_NAME;
    }

    public function createPaymentIntent(PaymentIntentRequest $request): PaymentIntentResult
    {
        $idempotencyKey = sprintf('psp:idempotency:%s', $request->idempotencyKey);
        if (null !== $existingId = $this->idempotencyStore->get($idempotencyKey)) {
            $intent = $this->loadIntent($existingId);

            return $this->toResult($intent);
        }

        $intent = [
            'id' => 'pi_'.bin2hex(random_bytes(12)),
            'order_id' => $request->orderId,
            'amount' => $request->amount,
            'currency' => strtolower($request->currency),
            'payment_method' => $request->paymentMethod,
            'status' => PaymentIntentResult::STATUS_REQUIRES_CONFIRMATION,
            'charge_id' => null,
            'failure_code' => null,
            'failure_message' => null,
            'metadata' => $request->metadata,
        ];

        $this->saveIntent($intent);
        $this->idempotencyStore->remember($idempotencyKey, $intent['id'], self::IDEMPOTENCY_TTL);

        return $this->toResult($intent);
    }

    public function confirmPaymentIntent(string $paymentIntentId): PaymentIntentResult
    {
        $intent = $this->loadIntent($paymentIntentId);

        if (PaymentIntentResult::STATUS_SUCCEEDED === $intent['status']) {
            return $this->toResult($intent);
        }

        $token = $intent['payment_method'];

        if (isset(self::DECLINED_TOKENS[$token])) {
            [$code, $message] = self::DECLINED_TOKENS[$token];
            $intent['status'] = PaymentIntentResult::STATUS_FAILED;
            $intent['failure_code'] = $code;
            $intent['failure_message'] = $message;
        } elseif (in_array($token, self::SUCCESSFUL_TOKENS, true)) {
            $intent['status'] = PaymentIntentResult::STATUS_SUCCEEDED;
            $intent['charge_id'] = 'ch_'.bin2hex(random_bytes(12));
        } else {
            $intent['status'] = PaymentIntentResult::STATUS_FAILED;
            $intent['failure_code'] = 'invalid_payment_method';
            $intent['failure_message'] = sprintf('Unknown test payment method "%s".', $token);
        }

        $this->saveIntent($intent);

        return $this->toResult($intent);
    }

    public function refundPaymentIntent(string $paymentIntentId, ?int $amount = null): RefundResult
    {
        $intent = $this->loadIntent($paymentIntentId);

        if (PaymentIntentResult::STATUS_SUCCEEDED !== $intent['status']) {
            throw new \RuntimeException('Only a succeeded payment can be refunded.');
        }

        $intent['status'] = 'refunded';
        $this->saveIntent($intent);

        return new RefundResult(
            're_'.bin2hex(random_bytes(12)),
            'succeeded',
            $amount ?? $intent['amount'],
            $paymentIntentId
        );
    }

    private function toResult(array $intent): PaymentIntentResult
    {
        return new PaymentIntentResult(
            $intent['id'],
            $intent['status'],
            $intent['amount'],
            $intent['currency'],
            $intent['charge_id'],
            $intent['failure_code'],
            $intent['failure_message'],
        );
    }

    private function loadIntent(string $paymentIntentId): array
    {
        $raw = $this->idempotencyStore->get(sprintf('psp:intent:%s', $paymentIntentId));
        if (null === $raw) {
            throw new \RuntimeException(sprintf('PaymentIntent "%s" not found.', $paymentIntentId));
        }

        return json_decode($raw, true);
    }

    private function saveIntent(array $intent): void
    {
        $this->idempotencyStore->remember(
            sprintf('psp:intent:%s', $intent['id']),
            json_encode($intent),
            self::INTENT_TTL
        );
    }
}
