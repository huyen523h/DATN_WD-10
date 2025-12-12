<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\GuideFeedback;
use App\Models\TourDeparture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TourFeedbackController extends Controller
{
    /**
     * Danh sách phản hồi của HDV
     */
    public function index(): View
    {
        $user = auth()->user();
        
        $feedbacks = GuideFeedback::where('guide_id', $user->id)
            ->with(['departure.tour'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('guide.feedback.index', compact('feedbacks'));
    }

    /**
     * Form tạo phản hồi mới
     */
    public function create($departureId): View
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->with(['tour'])
            ->findOrFail($departureId);

        return view('guide.feedback.create', compact('departure'));
    }

    /**
     * Lưu phản hồi mới
     */
    public function store(Request $request, $departureId)
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->findOrFail($departureId);

        $validated = $request->validate([
            'feedback_type' => 'required|in:tour,service,supplier,other',
            'subject' => 'required|string|max:255',
            'content' => 'required|string|min:20',
            'rating' => 'nullable|integer|min:1|max:5',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'supplier_name' => 'nullable|string|max:255',
            'suggestions' => 'nullable|string|max:1000',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('guide-feedback', 'public');
                $imagePaths[] = $path;
            }
        }

        GuideFeedback::create([
            'departure_id' => $departureId,
            'guide_id' => $user->id,
            'feedback_type' => $validated['feedback_type'],
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'rating' => $validated['rating'] ?? null,
            'images' => $imagePaths ?: null,
            'supplier_name' => $validated['supplier_name'] ?? null,
            'suggestions' => $validated['suggestions'] ?? null,
            'status' => 'pending', // Admin sẽ xem và xử lý
        ]);

        return redirect()
            ->route('guide.feedback.index')
            ->with('success', 'Đã gửi phản hồi thành công. Cảm ơn bạn đã đóng góp!');
    }

    /**
     * Xem chi tiết phản hồi
     */
    public function show($id): View
    {
        $user = auth()->user();
        
        $feedback = GuideFeedback::where('guide_id', $user->id)
            ->with(['departure.tour'])
            ->findOrFail($id);

        return view('guide.feedback.show', compact('feedback'));
    }

    /**
     * Form chỉnh sửa phản hồi
     */
    public function edit($id): View
    {
        $user = auth()->user();
        
        $feedback = GuideFeedback::where('guide_id', $user->id)
            ->with(['departure.tour'])
            ->findOrFail($id);

        // Chỉ cho phép sửa nếu status là pending
        if ($feedback->status !== 'pending') {
            abort(403, 'Không thể chỉnh sửa phản hồi đã được xử lý.');
        }

        return view('guide.feedback.edit', compact('feedback'));
    }

    /**
     * Cập nhật phản hồi
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        $feedback = GuideFeedback::where('guide_id', $user->id)
            ->findOrFail($id);

        if ($feedback->status !== 'pending') {
            abort(403, 'Không thể chỉnh sửa phản hồi đã được xử lý.');
        }

        $validated = $request->validate([
            'feedback_type' => 'required|in:tour,service,supplier,other',
            'subject' => 'required|string|max:255',
            'content' => 'required|string|min:20',
            'rating' => 'nullable|integer|min:1|max:5',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'supplier_name' => 'nullable|string|max:255',
            'suggestions' => 'nullable|string|max:1000',
            'remove_images' => 'nullable|array',
        ]);

        $imagePaths = $feedback->images ?? [];

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
                $path = $image->store('guide-feedback', 'public');
                $imagePaths[] = $path;
            }
        }

        $feedback->update([
            'feedback_type' => $validated['feedback_type'],
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'rating' => $validated['rating'] ?? null,
            'images' => $imagePaths ?: null,
            'supplier_name' => $validated['supplier_name'] ?? null,
            'suggestions' => $validated['suggestions'] ?? null,
        ]);

        return redirect()
            ->route('guide.feedback.show', $id)
            ->with('success', 'Đã cập nhật phản hồi thành công.');
    }

    /**
     * Xóa phản hồi
     */
    public function destroy($id)
    {
        $user = auth()->user();
        
        $feedback = GuideFeedback::where('guide_id', $user->id)
            ->findOrFail($id);

        if ($feedback->status !== 'pending') {
            abort(403, 'Không thể xóa phản hồi đã được xử lý.');
        }

        // Xóa ảnh
        if ($feedback->images) {
            foreach ($feedback->images as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        $feedback->delete();

        return redirect()
            ->route('guide.feedback.index')
            ->with('success', 'Đã xóa phản hồi thành công.');
    }
}
