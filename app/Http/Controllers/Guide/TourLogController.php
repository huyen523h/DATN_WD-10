<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\TourLog;
use App\Models\TourDeparture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TourLogController extends Controller
{
    /**
     * Danh sách nhật ký tour theo departure
     */
    public function index(Request $request, $departureId): View
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->with(['tour'])
            ->findOrFail($departureId);

        $logs = TourLog::where('departure_id', $departureId)
            ->where('guide_id', $user->id)
            ->orderBy('log_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guide.tour-logs.index', compact('departure', 'logs'));
    }

    /**
     * Form tạo nhật ký mới
     */
    public function create($departureId): View
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->with(['tour'])
            ->findOrFail($departureId);

        return view('guide.tour-logs.create', compact('departure'));
    }

    /**
     * Lưu nhật ký mới
     */
    public function store(Request $request, $departureId)
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->findOrFail($departureId);

        $validated = $request->validate([
            'log_date' => 'required|date',
            'type' => 'required|in:note,incident,feedback,other',
            'content' => 'required|string|min:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB per image
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('tour-logs', 'public');
                $imagePaths[] = $path;
            }
        }

        TourLog::create([
            'departure_id' => $departureId,
            'guide_id' => $user->id,
            'log_date' => $validated['log_date'],
            'type' => $validated['type'],
            'content' => $validated['content'],
            'images' => $imagePaths ?: null,
            'status' => 'active',
        ]);

        return redirect()
            ->route('guide.tour-logs.index', $departureId)
            ->with('success', 'Đã thêm nhật ký tour thành công.');
    }

    /**
     * Xem chi tiết nhật ký
     */
    public function show($departureId, $logId): View
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->with(['tour'])
            ->findOrFail($departureId);

        $log = TourLog::where('departure_id', $departureId)
            ->where('guide_id', $user->id)
            ->findOrFail($logId);

        return view('guide.tour-logs.show', compact('departure', 'log'));
    }

    /**
     * Form chỉnh sửa nhật ký
     */
    public function edit($departureId, $logId): View
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->with(['tour'])
            ->findOrFail($departureId);

        $log = TourLog::where('departure_id', $departureId)
            ->where('guide_id', $user->id)
            ->findOrFail($logId);

        return view('guide.tour-logs.edit', compact('departure', 'log'));
    }

    /**
     * Cập nhật nhật ký
     */
    public function update(Request $request, $departureId, $logId)
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->findOrFail($departureId);

        $log = TourLog::where('departure_id', $departureId)
            ->where('guide_id', $user->id)
            ->findOrFail($logId);

        $validated = $request->validate([
            'log_date' => 'required|date',
            'type' => 'required|in:note,incident,feedback,other',
            'content' => 'required|string|min:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'remove_images' => 'nullable|array',
        ]);

        $imagePaths = $log->images ?? [];

        // Xóa ảnh được chọn
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePaths = array_values(array_diff($imagePaths, [$imagePath]));
            }
        }

        // Thêm ảnh mới
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('tour-logs', 'public');
                $imagePaths[] = $path;
            }
        }

        $log->update([
            'log_date' => $validated['log_date'],
            'type' => $validated['type'],
            'content' => $validated['content'],
            'images' => $imagePaths ?: null,
        ]);

        return redirect()
            ->route('guide.tour-logs.show', [$departureId, $logId])
            ->with('success', 'Đã cập nhật nhật ký tour thành công.');
    }

    /**
     * Xóa nhật ký
     */
    public function destroy($departureId, $logId)
    {
        $user = auth()->user();
        
        $log = TourLog::where('departure_id', $departureId)
            ->where('guide_id', $user->id)
            ->findOrFail($logId);

        // Xóa ảnh
        if ($log->images) {
            foreach ($log->images as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        $log->delete();

        return redirect()
            ->route('guide.tour-logs.index', $departureId)
            ->with('success', 'Đã xóa nhật ký tour thành công.');
    }
}
