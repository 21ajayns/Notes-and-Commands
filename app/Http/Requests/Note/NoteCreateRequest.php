<?php
declare(strict_types=1);

namespace App\Http\Requests\Note;

use App\Constants\CategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NoteCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'string', Rule::in(CategoryEnum::toArray())],
            'folder_id' => ['nullable', 'string', 'exists:folders,id'],
        ];
    }
}