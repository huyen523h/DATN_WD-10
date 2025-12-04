<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupTourRequest;
use Illuminate\Support\Facades\Auth;

class GroupTourController extends Controller
{
    // 1. Hiển thị Form đặt tour
    public function create()
    {
        return view('group-tour.create');
    }

    // 2. Xử lý lưu Form
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date|after:today',
            'adults' => 'required|integer|min:1',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'email.required' => 'Vui lòng nhập email.',
            'destination.required' => 'Vui lòng nhập điểm đến.',
            'departure_date.required' => 'Vui lòng chọn ngày khởi hành.',
            'departure_date.after' => 'Ngày khởi hành phải là ngày trong tương lai.',
        ]);

        // Chuẩn bị dữ liệu
        $data = $request->all();
        
        // Nếu user đã đăng nhập, gắn ID vào
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        // Xử lý checkbox services (nếu không chọn thì để mảng rỗng)
        $data['services'] = $request->input('services', []);

        // Lưu vào database
        GroupTourRequest::create($data);

        return back()->with('success', 'Yêu cầu của bạn đã được gửi thành công! Nhân viên tư vấn sẽ liên hệ lại trong thời gian sớm nhất.');
    }
}