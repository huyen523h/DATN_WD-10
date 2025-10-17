<?php

namespace App\Http\Requests;

// Chú thích: Không cần dùng App\Models\Booking và App\Models\Tour nữa
// vì chúng ta không kiểm tra quyền hạn dựa trên đơn đặt hàng nữa.
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Chú thích: THAY ĐỔI QUAN TRỌNG NHẤT NẰM Ở ĐÂY.
        // Bằng cách trả về 'true', chúng ta cho phép bất kỳ ai đã vượt qua
        // middleware 'auth:sanctum' (tức là đã đăng nhập) đều có quyền thực hiện request này.
        return true;
    }

    /** 
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Chú thích: Các quy tắc validation cho dữ liệu gửi lên
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            // 'images' => 'nullable|array', // Nếu bạn muốn validation cả ảnh
            // 'images.*' => 'image|mimes:jpg,png,jpeg|max:2048'
        ];
    }
}