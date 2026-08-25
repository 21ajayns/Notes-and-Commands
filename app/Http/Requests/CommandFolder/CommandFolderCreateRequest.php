<?php
declare(strict_types=1);

namespace App\Http\Requests\CommandFolder;

use App\Constants\CategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommandFolderCreateRequest extends FormRequest
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
            'folder_id' => ['nullable', 'string', 'exists:command_folders,id'],
        ];
    }
}
