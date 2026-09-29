<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces\User;

use App\DataTransferObjects\User\UserRegisterDto;
use App\Models\User\User;

interface UserRepositoryInterface
{
    /**
     * Creates a new organization named after the person, and the user in it.
     */
    public function register(UserRegisterDto $registerDto): User;
}
