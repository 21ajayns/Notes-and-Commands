<?php
declare(strict_types=1);

namespace App\Http\Requests\CommandFolder;

use App\Constants\CategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommandFolderGetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'folder_id' => ['bail', 'nullable', 'string', 'uuid', Rule::exists('command_folders', 'id')->where('organization_id', $this->user()?->getAttribute('organization_id'))],
            'category' => ['nullable', 'string', Rule::in(CategoryEnum::toArray())],
        ];
    }
}
