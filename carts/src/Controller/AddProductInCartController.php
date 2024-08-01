<?php

namespace App\Controller;

use App\Controller\Http\Request\AddCartPayload;
use App\Service\AddToCartAction;
use App\Service\FindUserByEmailAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AddProductInCartController extends AbstractController
{
    public function __construct(public AddToCartAction $addToCartAction)
    {
    }

    #[Route('/api/cart/add-product', name: 'api_cart_add_product', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload]AddCartPayload $request): JsonResponse
    {
        $id = $this->addToCartAction->__invoke($request->productId, $request->userEmail, $request->quantity);
        return new JsonResponse(['id' => $id], 201);
    }
}