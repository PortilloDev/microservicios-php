<?php

namespace App\Controller;

use App\Controller\Http\Request\AddCartPayload;
use App\Service\AddToCartAction;
use App\Service\CloseCartAction;
use App\Service\FindCartByUserAction;
use App\Service\FindUserByEmailAction;
use App\Service\FindUserServiceAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class FindCartByUserController extends AbstractController
{
    public function __construct(public FindCartByUserAction $findCartByUserAction)
    {
    }

    #[Route('/api/cart/{userEmail}', name: 'api_find_cart_by_user', methods: ['GET'])]
    public function __invoke(string $userEmail): JsonResponse
    {
         $cart = $this->findCartByUserAction->__invoke($userEmail);
         if (!$cart) {
             return new JsonResponse([], 404);
         }

         return new JsonResponse($cart->toArray(), 200);
    }
}