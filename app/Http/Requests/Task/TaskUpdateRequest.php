<?php
declare(strict_types=1);

namespace App\Http\Requests\Task;

use App\Constants\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', 'string', Rule::in(TaskStatusEnum::toArray())],
        ];
    }
}
