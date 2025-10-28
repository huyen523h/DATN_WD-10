<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTourImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_cover'   => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }
}
    