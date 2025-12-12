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
        
        // Email không bắt buộc khi edit, nhưng nếu có thì phải unique
        $rules['email'] = ['nullable', 'email', 'max:255', Rule::unique('users', 'email')];

        return $rules;
    }
}

