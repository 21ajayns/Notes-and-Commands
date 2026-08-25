<?php
declare(strict_types=1);

namespace App\Http\Requests\CommandFolder;

use Illuminate\Foundation\Http\FormRequest;

class CommandFolderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
