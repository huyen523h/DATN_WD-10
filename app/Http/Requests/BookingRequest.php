<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
            'tour_id' => 'required|exists:tours,id',
            'departure_id' => 'required|exists:tour_departures,id',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'integer|min:0|max:10',
            'infants' => 'integer|min:0|max:5',
            'promotion_code' => 'nullable|exists:promotions,code',
            'note' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tour_id.required' => 'Vui lòng chọn tour.',
            'tour_id.exists' => 'Tour không tồn tại.',
            'departure_id.required' => 'Vui lòng chọn ngày khởi hành.',
            'departure_id.exists' => 'Ngày khởi hành không tồn tại.',
            'adults.required' => 'Vui lòng nhập số lượng người lớn.',
            'adults.min' => 'Số lượng người lớn phải ít nhất 1 người.',
            'adults.max' => 'Số lượng người lớn không được vượt quá 10 người.',
            'children.min' => 'Số lượng trẻ em không được âm.',
            'children.max' => 'Số lượng trẻ em không được vượt quá 10 người.',
            'infants.min' => 'Số lượng em bé không được âm.',
            'infants.max' => 'Số lượng em bé không được vượt quá 5 người.',
            'promotion_code.exists' => 'Mã giảm giá không hợp lệ.',
            'note.max' => 'Ghi chú không được vượt quá 1000 ký tự.',
        ];
    }
}
