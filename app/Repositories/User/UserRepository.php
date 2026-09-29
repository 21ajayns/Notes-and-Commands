<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\DataTransferObjects\User\UserRegisterDto;
use App\Models\Organization\Organization;
use App\Models\User\User;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function register(UserRegisterDto $registerDto): User
    {
        return DB::transaction(function () use ($registerDto): User {
            $organization = new Organization();
            $organization->setAttribute('name', $registerDto->getName());

            $organization->save();

            $user = new User();
            $user->setAttribute('organization_id', $organization->getId());
            $user->setAttribute('name', $registerDto->getName());
            $user->setAttribute('email', $registerDto->getEmail());
            $user->setAttribute('password', $registerDto->getPassword());

            $user->save();

            return $user;
        });
    }
}
