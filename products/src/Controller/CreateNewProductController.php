<?php

namespace App\Controller;

use App\Controller\Http\Request\CreateProductPayload;
use App\Service\CreateProductAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
class CreateNewProductController extends AbstractController
{
    public function __construct(private CreateProductAction $createNewProductAction)
    {
    }

    #[Route('/api/product', name: 'api_create_product', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload]CreateProductPayload $request): JsonResponse
    {
        $product = $this->createNewProductAction->__invoke($request->name, $request->price, $request->description);
        return new JsonResponse(['id' => $product->getId()], 201);
    }
}