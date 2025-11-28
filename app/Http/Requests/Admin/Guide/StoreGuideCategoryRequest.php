<?php

namespace App\Http\Requests\Admin\Guide;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuideCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $categoryId = $this->route('guide_category');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('guide_categories', 'slug')->ignore($categoryId),
            ],
            'type' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_default' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
}

