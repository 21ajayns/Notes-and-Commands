<?php
declare(strict_types=1);

namespace App\Http\Requests\Task;

use App\Constants\CategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', Rule::in(CategoryEnum::toArray())],
        ];
    }
}
