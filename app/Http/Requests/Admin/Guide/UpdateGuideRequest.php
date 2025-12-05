<?php

namespace App\Http\Requests\Admin\Guide;

use Illuminate\Validation\Rule;

class UpdateGuideRequest extends StoreGuideRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $guideId = $this->route('guide');

        $rules['code'] = [
            'required',
            'string',
            'max:50',
            Rule::unique('guides', 'code')->ignore($guideId),
        ];

        // Khi update, các field tạo tài khoản có thể bỏ trống nếu không dùng
        $rules['user_email'] = ['nullable', 'email', 'max:255'];
        $rules['user_password'] = ['nullable', 'string', 'min:6', 'max:100'];

        return $rules;
    }
}

