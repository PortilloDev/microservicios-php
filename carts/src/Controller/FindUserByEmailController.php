<?php

namespace App\Controller;

use App\Service\FindUserByEmailAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class FindUserByEmailController extends AbstractController
{
    public function __construct(private FindUserByEmailAction $findUserByEmailAction)
    {
    }

    #[Route('/api/internal/search-user', name: 'search_user', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        if (null === $email = $request->query->get('email')) {
            return new JsonResponse(['error' => 'Email param is required'], 400);

        }
       if (null === $user = $this->findUserByEmailAction->__invoke($email)) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        return new JsonResponse($user->toArray());
    }
}