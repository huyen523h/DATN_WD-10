<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ReplaceTourImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images'   => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:2048',
        ];
    }
}
