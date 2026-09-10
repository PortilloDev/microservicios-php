<?php

namespace App\Controller;

use App\Service\CloseCartAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CloseCartController extends AbstractController
{
    public function __construct(public CloseCartAction $closeCartAction)
    {
    }

    #[Route('/api/cart/{id}/close', name: 'api_close_cart', methods: ['PATCH'])]
    public function __invoke(string $id): JsonResponse
    {
        $cart = $this->closeCartAction->__invoke($id);

        return new JsonResponse([
            'id' => $cart->getId(),
            'status' => $cart->getStatus(),
        ], 202);
    }
}
