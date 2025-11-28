<?php

namespace App\Http\Requests\Admin\Operation;

use Illuminate\Foundation\Http\FormRequest;

class AssignStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'guide_id' => ['nullable', 'integer', 'exists:guides,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'external_name' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:50'],
            'assignment_type' => ['required', 'in:internal,external'],
            'status' => ['nullable', 'in:pending,notified,confirmed,declined'],
            'notes' => ['nullable', 'string'],
        ];
    }
}

