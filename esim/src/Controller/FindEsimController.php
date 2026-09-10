<?php

namespace App\Controller;

use App\Service\FindEsimAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class FindEsimController extends AbstractController
{
    public function __construct(private FindEsimAction $findEsimAction)
    {
    }

    #[Route('/api/esim/{id}', name: 'api_find_esim', methods: ['GET'])]
    public function byId(string $id): JsonResponse
    {
        $esim = $this->findEsimAction->byId($id);
        if (null === $esim) {
            return new JsonResponse([], 404);
        }

        return new JsonResponse($esim->toArray(), 200);
    }

    #[Route('/api/internal/esim/order/{orderId}', name: 'api_internal_find_esim_by_order', methods: ['GET'])]
    public function byOrderId(string $orderId): JsonResponse
    {
        $esim = $this->findEsimAction->byOrderId($orderId);
        if (null === $esim) {
            return new JsonResponse([], 404);
        }

        return new JsonResponse($esim->toArray(), 200);
    }

    #[Route('/api/esim', name: 'api_list_esims', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $esims = array_map(
            static fn ($esim) => $esim->toArray(),
            $this->findEsimAction->list()
        );

        return new JsonResponse($esims, 200);
    }
}
