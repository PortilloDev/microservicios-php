<?php

namespace App\Controller;

use App\Controller\Http\Request\InventoryPayload;
use App\Service\UpdateProductInInventoryAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateProductInInventoryController extends AbstractController
{
    public function __construct(public UpdateProductInInventoryAction $action)
    {
    }

    #[Route('/api/inventory', name: 'api_update_inventory', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] InventoryPayload $request
    ): JsonResponse {

        $this->action->__invoke($request->productId, $request->quantity);
        return new JsonResponse();
    }
}