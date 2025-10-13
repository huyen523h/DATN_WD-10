<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreTourImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // TODO: gắn policy hoặc middleware auth khi cần
    }

    public function rules(): array
    {
        return [
            'images'   => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'images.required'   => 'Vui lòng chọn ít nhất 1 ảnh.',
            'images.*.image'    => 'Tệp phải là hình ảnh hợp lệ.',
            'images.*.mimes'    => 'Chỉ chấp nhận jpeg, jpg, png, webp, gif.',
            'images.*.max'      => 'Mỗi ảnh tối đa 2MB.',
        ];
    }
}
