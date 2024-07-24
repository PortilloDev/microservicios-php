<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class FindUserByEmailAction
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(string $email): ?User
    {
        return $this->userRepository->findOneByEmail($email);
    }

}