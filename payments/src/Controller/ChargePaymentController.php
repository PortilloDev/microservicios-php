<?php

namespace App\Controller;

use App\Controller\Http\Request\ChargePayload;
use App\Messenger\Message\CheckoutRequestedMessage;
use App\Messenger\RoutingKey;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class ChargePaymentController extends AbstractController
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    #[Route('/api/payment/charge', name: 'api_payment_charge', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] ChargePayload $request): JsonResponse
    {
        $this->messageBus->dispatch(
            new CheckoutRequestedMessage(
                $request->orderId,
                $request->userEmail,
                $request->amount,
                $request->currency,
                $request->paymentMethod,
            ),
            [new AmqpStamp(RoutingKey::CHECKOUT_PAYMENT_QUEUE)]
        );

        return new JsonResponse(['orderId' => $request->orderId, 'status' => 'accepted'], 202);
    }
}
