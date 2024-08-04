<?php

namespace App\Controller;

use App\Controller\Http\Request\InventoryPayload;
use App\Service\ListInventoryAction;
use App\Service\UpdateProductInInventoryAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ListInventoryController extends AbstractController
{
    public function __construct(public ListInventoryAction $action)
    {
    }

    #[Route('/api/inventory/', name: 'api_list_inventory', methods: ['GET'])]
    public function __invoke(): JsonResponse {

       $list =  $this->action->__invoke();
        return new JsonResponse(array_map(fn($inventory) => $inventory->toArray(), $list));
    }
}