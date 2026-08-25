<?php
declare(strict_types=1);

namespace App\Http\Requests\Command;

use Illuminate\Foundation\Http\FormRequest;

class CommandUpdateRequest extends FormRequest
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
        ];
    }
}
