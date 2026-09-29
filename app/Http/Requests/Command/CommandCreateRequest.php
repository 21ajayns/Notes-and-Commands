<?php
declare(strict_types=1);

namespace App\Http\Requests\Command;

use App\Constants\CategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommandCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'rows' => ['present', 'array'],
            'rows.*.label' => ['required', 'string', 'max:255'],
            'rows.*.value' => ['required', 'string'],
            'category' => ['required', 'string', Rule::in(CategoryEnum::toArray())],
            'folder_id' => ['bail', 'nullable', 'string', 'uuid', Rule::exists('command_folders', 'id')->where('organization_id', $this->user()?->getAttribute('organization_id'))],
        ];
    }
}
