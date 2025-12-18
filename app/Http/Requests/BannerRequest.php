<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'image_url' => 'required|url|max:500',
            'link_url' => 'nullable|url|max:500',
            'type' => 'required|in:hero,promotion,category,featured',
            'position' => 'required|in:top,middle,bottom,sidebar',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_audience' => 'nullable|array',
            'target_audience.*' => 'in:all,new_users,returning_users',
        ];

        // For update requests, make some fields optional
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['title'] = 'sometimes|required|string|max:200';
            $rules['image_url'] = 'sometimes|required|url|max:500';
            $rules['type'] = 'sometimes|required|in:hero,promotion,category,featured';
            $rules['position'] = 'sometimes|required|in:top,middle,bottom,sidebar';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề banner là bắt buộc',
            'title.max' => 'Tiêu đề không được vượt quá 200 ký tự',
            'image_url.required' => 'URL hình ảnh là bắt buộc',
            'image_url.url' => 'URL hình ảnh không hợp lệ',
            'image_url.max' => 'URL hình ảnh không được vượt quá 500 ký tự',
            'link_url.url' => 'URL liên kết không hợp lệ',
            'link_url.max' => 'URL liên kết không được vượt quá 500 ký tự',
            'type.required' => 'Loại banner là bắt buộc',
            'type.in' => 'Loại banner không hợp lệ',
            'position.required' => 'Vị trí banner là bắt buộc',
            'position.in' => 'Vị trí banner không hợp lệ',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên',
            'sort_order.min' => 'Thứ tự sắp xếp không được nhỏ hơn 0',
            'is_active.boolean' => 'Trạng thái hoạt động phải là true hoặc false',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'end_date.date' => 'Ngày kết thúc không hợp lệ',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
            'target_audience.array' => 'Đối tượng mục tiêu phải là mảng',
            'target_audience.*.in' => 'Đối tượng mục tiêu không hợp lệ',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'tiêu đề',
            'description' => 'mô tả',
            'image_url' => 'URL hình ảnh',
            'link_url' => 'URL liên kết',
            'type' => 'loại banner',
            'position' => 'vị trí',
            'sort_order' => 'thứ tự sắp xếp',
            'is_active' => 'trạng thái hoạt động',
            'start_date' => 'ngày bắt đầu',
            'end_date' => 'ngày kết thúc',
            'target_audience' => 'đối tượng mục tiêu',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default values
        if (!$this->has('sort_order')) {
            $this->merge(['sort_order' => 0]);
        }

        if (!$this->has('is_active')) {
            $this->merge(['is_active' => true]);
        }

        if (!$this->has('target_audience')) {
            $this->merge(['target_audience' => ['all']]);
        }

        // Convert string dates to proper format
        if ($this->has('start_date') && is_string($this->start_date)) {
            $this->merge(['start_date' => \Carbon\Carbon::parse($this->start_date)]);
        }

        if ($this->has('end_date') && is_string($this->end_date)) {
            $this->merge(['end_date' => \Carbon\Carbon::parse($this->end_date)]);
        }
    }
}
