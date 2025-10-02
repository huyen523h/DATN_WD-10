<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check(); // Chỉ user đăng nhập mới tạo
    }

    public function rules()
    {
        return [
            'tour_id' => ['required', 'exists:tours,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
            'images' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Upload ảnh, max 2MB
            'status' => ['nullable', Rule::in(['active', 'pending'])], // Chỉ admin set, user mặc định active
        ];
    }

    public function messages()
    {
        return [
            'rating.min' => 'Rating phải từ 1 đến 5 sao.',
            'rating.max' => 'Rating phải từ 1 đến 5 sao.',
            'images.image' => 'File phải là hình ảnh.',
            'status.in' => 'Status phải là active hoặc pending.',
        ];
    }
}