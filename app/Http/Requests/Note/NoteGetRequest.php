<?php
declare(strict_types=1);

namespace App\Http\Requests\Note;

use App\Constants\CategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NoteGetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'folder_id' => ['nullable', 'string', 'exists:folders,id'],
            'category' => ['nullable', 'string', Rule::in(CategoryEnum::toArray())],
        ];
    }
}
