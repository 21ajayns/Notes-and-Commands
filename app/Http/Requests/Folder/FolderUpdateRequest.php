<?php
declare(strict_types=1);

namespace App\Http\Requests\Folder;

use Illuminate\Foundation\Http\FormRequest;

class FolderUpdateRequest extends FormRequest
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
