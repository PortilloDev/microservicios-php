<?php

namespace App\Controller;

use App\Service\FindPaymentAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class FindPaymentController extends AbstractController
{
    public function __construct(private FindPaymentAction $findPaymentAction)
    {
    }

    #[Route('/api/payment/{id}', name: 'api_find_payment', methods: ['GET'])]
    public function byId(string $id): JsonResponse
    {
        $payment = $this->findPaymentAction->byId($id);
        if (null === $payment) {
            return new JsonResponse([], 404);
        }

        return new JsonResponse($payment->toArray(), 200);
    }

    #[Route('/api/internal/payment/order/{orderId}', name: 'api_internal_find_payment_by_order', methods: ['GET'])]
    public function byOrderId(string $orderId): JsonResponse
    {
        $payment = $this->findPaymentAction->byOrderId($orderId);
        if (null === $payment) {
            return new JsonResponse([], 404);
        }

        return new JsonResponse($payment->toArray(), 200);
    }

    #[Route('/api/payment', name: 'api_list_payments', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $payments = array_map(
            static fn ($payment) => $payment->toArray(),
            $this->findPaymentAction->list()
        );

        return new JsonResponse($payments, 200);
    }
}
