<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class RegisterFormController extends Controller
{
    public function __invoke(): View
    {
        return \view('auth.register');
    }
}
