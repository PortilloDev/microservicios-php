<?php

namespace App\Controller;

use App\Controller\Http\Request\AddCartPayload;
use App\Service\AddToCartAction;
use App\Service\CloseCartAction;
use App\Service\FindUserByEmailAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CloseCartController extends AbstractController
{
    public function __construct(public CloseCartAction $closeCartAction)
    {
    }

    #[Route('/api/cart/{id}/close', name: 'api_close_cart', methods: ['PATCH'])]
    public function __invoke(string $id): JsonResponse
    {
        $this->closeCartAction->__invoke($id);
        return new JsonResponse([], 204);
    }
}