<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourLog;
use App\Models\TourDeparture;
use Illuminate\Http\Request;

class TourLogController extends Controller
{
    /**
     * Danh sách toàn bộ nhật ký tour
     */
    public function index(Request $request)
    {
        $logs = TourLog::with(['departure.tour', 'guide'])
            ->orderBy('log_date', 'desc')
            ->paginate(15);

        return view('admin.tour-logs.index', compact('logs'));
    }
    /**
     * Form tạo nhật ký tour
     */
    public function create()
    {
        $departures = TourDeparture::with('tour')
            ->orderBy('departure_date', 'desc')
            ->get();

        return view('admin.tour-logs.create', compact('departures'));
    }

    /**
     * Lưu nhật ký mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'departure_id' => 'required|exists:tour_departures,id',
            'log_date'     => 'required|date',
            'type'         => 'required|string',
            'content'      => 'required|string',
            'images.*'     => 'nullable|image|max:4096'
        ]);

        // Upload ảnh
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->images as $img) {
                $images[] = $img->store('tour_logs', 'public');
            }
        }

        // Tạo nhật ký
        TourLog::create([
            'departure_id' => $validated['departure_id'],
            'guide_id'     => auth()->id(),
            'log_date'     => $validated['log_date'],
            'type'         => $validated['type'],
            'content'      => $validated['content'],
            'images'       => $images,
            'status'       => 'active'
        ]);

        return redirect()
            ->route('admin.tour-logs.index')
            ->with('success', 'Đã tạo nhật ký tour!');
    }

    /**
     * Xem chi tiết nhật ký
     */
    public function show($id)
    {
        $log = TourLog::with(['departure.tour', 'guide'])
            ->findOrFail($id);

        return view('admin.tour-logs.show', compact('log'));
    }

    /**
     * Form chỉnh sửa nhật ký
     */
    public function edit($id)
    {
        $log = TourLog::findOrFail($id);
        $departures = TourDeparture::with('tour')->get();

        return view('admin.tour-logs.edit', compact('log', 'departures'));
    }

    /**
     * Cập nhật nhật ký
     */
    public function update(Request $request, $id)
    {
        $log = TourLog::findOrFail($id);

        $validated = $request->validate([
            'departure_id' => 'required|exists:tour_departures,id',
            'log_date'     => 'required|date',
            'type'         => 'required|string',
            'content'      => 'required|string',
            'images.*'     => 'nullable|image|max:4096'
        ]);

        // Nếu upload ảnh mới → ghi đè
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->images as $img) {
                $images[] = $img->store('tour_logs', 'public');
            }

            // Xóa ảnh cũ
            if (!empty($log->images)) {
                foreach ($log->images as $old) {
                    @unlink(storage_path('app/public/' . $old));
                }
            }

            $log->images = $images;
        }

        $log->update([
            'departure_id' => $validated['departure_id'],
            'log_date'     => $validated['log_date'],
            'type'         => $validated['type'],
            'content'      => $validated['content']
        ]);

        return redirect()
            ->route('admin.tour-logs.index')
            ->with('success', 'Nội dung nhật ký đã được cập nhật!');
    }

    /**
     * Xóa nhật ký
     */
    public function destroy($id)
    {
        $log = TourLog::findOrFail($id);

        // Xóa ảnh
        if (!empty($log->images)) {
            foreach ($log->images as $old) {
                @unlink(storage_path('app/public/' . $old));
            }
        }

        $log->delete();

        return redirect()
            ->route('admin.tour-logs.index')
            ->with('success', 'Nhật ký đã được xóa!');
    }
}
