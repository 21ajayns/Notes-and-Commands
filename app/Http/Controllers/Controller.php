<?php

namespace App\Http\Controllers;

use App\Models\User\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * The organization every read and write is scoped to: the signed-in user's.
     */
    protected function organizationId(): string
    {
        $user = \auth()->user();

        if ($user instanceof User === false) {
            \abort(401);
        }

        return $user->getOrganizationId();
    }
}
