<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): RedirectResponse
    {
        if (Auth::attempt($request->validated(), $request->boolean('remember')) === false) {
            return \back()
                ->withErrors(['email' => 'That email and password don’t match an account.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return \redirect()->intended('/');
    }
}
