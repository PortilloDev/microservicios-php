<?php

namespace App\Repository;

class UserRepository
{
     public function findOneByEmail(string $email): ?User
     {
         // some logic to find user by email
     }
}