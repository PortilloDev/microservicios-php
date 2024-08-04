<?php

namespace App\Controller;

use App\Controller\Http\Request\InventoryPayload;
use App\Service\ObtainStockFromInventoryAction;
use App\Service\UpdateProductInInventoryAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ObtainStockFromProductController extends AbstractController
{
    public function __construct(public ObtainStockFromInventoryAction $action)
    {
    }

    #[Route('/api/internal/inventory/{productId}', name: 'api_internal_obtain_stock', methods: ['GET'])]
    public function __invoke(string $productId
    ): JsonResponse {

        $inventory = $this->action->__invoke($productId);
        if (!$inventory){
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['quantity' => $inventory->getQuantity()]);
    }
}