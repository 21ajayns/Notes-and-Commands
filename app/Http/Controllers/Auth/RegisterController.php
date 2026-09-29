<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\DataTransferObjects\User\UserRegisterDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\Interfaces\User\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(RegisterRequest $request): RedirectResponse
    {
        $user = $this->userRepository->register(new UserRegisterDto(
            $request->validated('name'),
            $request->validated('email'),
            $request->validated('password')
        ));

        Auth::login($user);

        $request->session()->regenerate();

        return \redirect('/');
    }
}
