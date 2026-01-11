<?php

// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// use App\Models\Booking;
// use App\Models\Payment;
// use Illuminate\Http\Request;

// class CustomerController extends Controller
// {
//     /** 
//      * DANH SÁCH KHÁCH HÀNG
//      */
//     public function index(Request $request)
//     {
//         $query = User::where('role', 'customer');

//         // Search theo tên / email / sđt
//         if ($request->search) {
//             $query->where(function($q) use ($request) {
//                 $q->where('name', 'like', '%'.$request->search.'%')
//                   ->orWhere('email', 'like', '%'.$request->search.'%')
//                   ->orWhere('phone', 'like', '%'.$request->search.'%');
//             });
//         }

//         $customers = $query->orderBy('id', 'desc')->paginate(20);

//         return view('admin.customers.index', compact('customers'));
//     }

//     /**
//      * TRANG CHI TIẾT KHÁCH HÀNG
//      */
//     public function show($id)
//     {
//         $customer = User::findOrFail($id);

//         // Lấy danh sách booking
//         $bookings = Booking::with(['tour', 'payments'])
//             ->where('user_id', $id)
//             ->orderBy('id', 'desc')
//             ->get();

//         // Tổng số tour đã đặt
//         $totalTours = $bookings->count();

//         // Tổng tiền đã chi
//         $totalPaid = Payment::whereHas('booking', function($q) use ($id) {
//             $q->where('user_id', $id);
//         })->where('status', 'completed')->sum('amount');

//         return view('admin.customers.show', compact(
//             'customer',
//             'bookings',
//             'totalTours',
//             'totalPaid'
//         ));
//     }
// }



namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('bookings')
            ->orderBy('created_at', 'desc');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->paginate(15);

        return view('admin.customer.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customer.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $validated['password'] = bcrypt('12345678');
        $validated['role'] = 'customer';

        User::create($validated);

        return redirect()->route('admin.customer.index')
            ->with('success', 'Thêm khách hàng thành công!');
    }

    public function show($id)
    {
        $customer = User::with([
            'bookings.tour',
            'bookings.departure',
            'bookings.passengers',
            'bookings.payment',
        ])->findOrFail($id);
        return view('admin.customer.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = User::findOrFail($id);
        return view('admin.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customer.index')
            ->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('admin.customer.index')
            ->with('success', 'Xóa khách hàng thành công!');
    }
}
