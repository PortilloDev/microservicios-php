<?php

namespace App\Controller;

use App\Controller\Http\Request\RefundPayload;
use App\Service\RefundPaymentAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class RefundPaymentController extends AbstractController
{
    public function __construct(private RefundPaymentAction $refundPaymentAction)
    {
    }

    #[Route('/api/internal/payment/{id}/refund', name: 'api_internal_refund_payment', methods: ['POST'])]
    public function __invoke(string $id, #[MapRequestPayload] RefundPayload $request): JsonResponse
    {
        $refund = $this->refundPaymentAction->__invoke($id, $request->amount);

        return new JsonResponse($refund->toArray(), 201);
    }
}
