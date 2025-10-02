<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReviewRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check(); // Chỉ user đăng nhập mới update
    }

    public function rules()
    {
        return [
            'rating' => ['sometimes', 'integer', 'min:1', 'max:5'], // Bỏ 'required' để optional
            'comment' => ['sometimes', 'string', 'max:500'], // Optional
            'images' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Optional update ảnh
            'status' => ['sometimes', Rule::in(['active', 'pending', 'deleted'])], // Optional, bỏ 'required'
        ];
    }

    public function messages()
    {
        return [
            'rating.min' => 'Rating phải từ 1 đến 5 sao.',
            'rating.max' => 'Rating phải từ 1 đến 5 sao.',
            'images.image' => 'File phải là hình ảnh.',
            'status.in' => 'Status phải là active, pending hoặc deleted.',
        ];
    }
}