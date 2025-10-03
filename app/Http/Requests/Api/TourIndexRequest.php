<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TourIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
            'location' => ['nullable', 'string', 'max:255'],
            'sort_by' => ['nullable', 'string', 'in:price,created_at,title'],
            'sort_direction' => ['nullable', 'string', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'search.max' => 'Từ khóa tìm kiếm không được vượt quá 255 ký tự.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'min_price.numeric' => 'Giá tối thiểu phải là số.',
            'min_price.min' => 'Giá tối thiểu không được nhỏ hơn 0.',
            'max_price.numeric' => 'Giá tối đa phải là số.',
            'max_price.min' => 'Giá tối đa không được nhỏ hơn 0.',
            'max_price.gte' => 'Giá tối đa phải lớn hơn hoặc bằng giá tối thiểu.',
            'location.max' => 'Địa điểm không được vượt quá 255 ký tự.',
            'sort_by.in' => 'Trường sắp xếp không hợp lệ.',
            'sort_direction.in' => 'Hướng sắp xếp không hợp lệ.',
            'per_page.integer' => 'Số lượng mỗi trang phải là số nguyên.',
            'per_page.min' => 'Số lượng mỗi trang phải lớn hơn 0.',
            'per_page.max' => 'Số lượng mỗi trang không được vượt quá 100.',
            'page.integer' => 'Số trang phải là số nguyên.',
            'page.min' => 'Số trang phải lớn hơn 0.',
        ];
    }

    /**
     * Get the validated data with defaults.
     *
     * @return array<string, mixed>
     */
    public function validatedWithDefaults(): array
    {
        $validated = $this->validated();

        return array_merge([
            'search' => null,
            'category_id' => null,
            'min_price' => null,
            'max_price' => null,
            'location' => null,
            'sort_by' => 'created_at',
            'sort_direction' => 'desc',
            'per_page' => 15,
            'page' => 1,
        ], $validated);
    }
}
