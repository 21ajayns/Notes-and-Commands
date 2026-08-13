<?php
declare(strict_types=1);

namespace App\Http\Requests\Folder;

use App\Constants\CategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FolderCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', Rule::in(CategoryEnum::toArray())],
            'folder_id' => ['nullable', 'string', 'exists:folders,id'],
        ];
    }
}