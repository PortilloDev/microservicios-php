<?php

namespace App\Controller;

use App\Controller\Http\Request\ProvisionPayload;
use App\Service\ProvisionEsimAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ProvisionEsimController extends AbstractController
{
    public function __construct(private ProvisionEsimAction $provisionEsimAction)
    {
    }

    #[Route('/api/internal/esim/provision', name: 'api_internal_provision_esim', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] ProvisionPayload $request): JsonResponse
    {
        $esim = ($this->provisionEsimAction)(
            $request->orderId,
            $request->userEmail,
            $request->plan,
            $request->paymentId
        );

        return new JsonResponse($esim->toArray(), 201);
    }
}
