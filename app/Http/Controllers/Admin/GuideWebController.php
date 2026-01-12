<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Guide\StoreGuideRequest;
use App\Http\Requests\Admin\Guide\UpdateGuideRequest;
use App\Models\Guide;
use App\Models\GuideCategory;
use App\Models\User;
use App\Services\GuideService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuideWebController extends Controller
{
    public function __construct(
        protected GuideService $guideService
    ) {
    }

    public function index(Request $request): View
    {
        $query = Guide::with(['categories']);
        
        // Filter by keyword
        if ($request->filled('keyword')) {
            $keyword = $request->string('keyword')->toString();
            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")
                    ->orWhere('code', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }
        
        // Filter by category
        if ($request->filled('category_id')) {
            $categoryId = $request->integer('category_id');
            $query->whereHas('categories', function ($catQuery) use ($categoryId) {
                $catQuery->where('guide_categories.id', $categoryId);
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $status = $request->string('status')->toString();
            $query->where('status', $status);
        }
        
        $guides = $query->orderBy('full_name')
            ->paginate(10)
            ->appends($request->only(['keyword', 'category_id', 'status']));

        $categories = GuideCategory::orderBy('name')->get();

        return view('admin.guides.index', compact('guides', 'categories'));
    }

    public function create(): View
    {
        $categories = GuideCategory::orderBy('name')->get();
        $guideUsers = User::whereHas('roles', fn($q) => $q->where('name', 'guide'))
            ->orderBy('name')
            ->get();
        return view('admin.guides.create', [
            'guide' => new Guide(),
            'categories' => $categories,
            'guideUsers' => $guideUsers,
        ]);
    }

    public function store(StoreGuideRequest $request): RedirectResponse
    {
        $guide = $this->guideService->create($request->validated());
        
        // Reload guide để lấy metadata đầy đủ
        $guide->refresh();
        
        // Lấy thông tin password và email
        $email = $guide->email ?? 'N/A';
        $password = null;
        
        if ($guide->metadata) {
            $metadata = is_string($guide->metadata) ? json_decode($guide->metadata, true) : $guide->metadata;
            if (is_array($metadata) && isset($metadata['initial_password'])) {
                $password = $metadata['initial_password'];
            }
        }
        
        // Tạo message với HTML để hiển thị đẹp
        $message = "Đã tạo hồ sơ hướng dẫn viên <strong>{$guide->full_name}</strong>";
        
        if ($password) {
            $message .= "<br><br><strong>Thông tin đăng nhập:</strong>";
            $message .= "<br>📧 Email: <code>{$email}</code>";
            $message .= "<br>🔑 Mật khẩu: <code>{$password}</code>";
            $message .= "<br><br><small class='text-warning'>⚠️ Vui lòng lưu lại thông tin đăng nhập này để cấp cho HDV.</small>";
        } else {
            $message .= "<br><br>Email: <code>{$email}</code>";
        }

        return redirect()->route('admin.guides.index')
            ->with('success', $message);
    }

    public function show(Guide $guide): View
    {
        $guide->load(['categories', 'languages', 'documents', 'healthRecords']);
        return view('admin.guides.show', compact('guide'));
    }

    public function edit(Guide $guide): View
    {
        $guide->load(['categories', 'languages', 'documents', 'healthRecords']);
        $categories = GuideCategory::orderBy('name')->get();
        $guideUsers = User::whereHas('roles', fn($q) => $q->where('name', 'guide'))
            ->orderBy('name')
            ->get();

        return view('admin.guides.edit', compact('guide', 'categories', 'guideUsers'));
    }

    public function update(UpdateGuideRequest $request, Guide $guide): RedirectResponse
    {
        $guide = $this->guideService->update($guide, $request->validated());

        return redirect()->route('admin.guides.index')
            ->with('success', "Đã cập nhật hồ sơ {$guide->full_name}");
    }

    public function destroy(Guide $guide): RedirectResponse
    {
        $guide->delete();

        return redirect()->route('admin.guides.index')
            ->with('success', 'Đã xoá hồ sơ hướng dẫn viên');
    }
}

