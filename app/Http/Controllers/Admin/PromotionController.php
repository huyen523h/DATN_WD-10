<?php

namespace App\Http\Controllers\Admin; // <--- QUAN TRỌNG: Namespace phải là Admin

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::orderBy('id', 'desc')->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:promotions,code|max:20|alpha_num',
            'discount_amount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($request->code);
        $data['used_count'] = 0; 

        Promotion::create($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Tạo mã thành công!');
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        if ($request->has('status') && !$request->has('code')) {
            $promotion->update(['status' => $request->status]);
            $msg = $request->status == 'active' ? 'Đã kích hoạt mã!' : 'Đã tạm dừng mã!';
            return back()->with('success', $msg);
        }
        
        $request->validate([
            'code' => 'required|max:20|alpha_num|unique:promotions,code,'.$id,
            'discount_amount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($request->quantity < $promotion->used_count) {
            return back()->with('error', "Không thể giảm số lượng thấp hơn số đã dùng ({$promotion->used_count}).");
        }

        $data = $request->all();
        $data['code'] = strtoupper($request->code);

        $promotion->update($data);

       return redirect()->route('admin.promotions.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        if ($promotion->used_count > 0) {
            return back()->with('error', 'Mã đã có người dùng, không thể xóa!');
        }
        $promotion->delete();
        return back()->with('success', 'Đã xóa mã.');
    }
    public function show(string $id)
    {
        $promotion = Promotion::findOrFail($id);
        return view('admin.promotions.show', compact('promotion'));
    }
}