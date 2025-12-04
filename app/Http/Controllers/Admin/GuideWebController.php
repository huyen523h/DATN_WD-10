<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Guide\StoreGuideRequest;
use App\Http\Requests\Admin\Guide\UpdateGuideRequest;
use App\Models\Guide;
use App\Models\GuideCategory;
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
        $guides = Guide::with(['categories'])
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->string('keyword')->toString();
                $query->where(function ($q) use ($keyword) {
                    $q->where('full_name', 'like', "%{$keyword}%")
                        ->orWhere('code', 'like', "%{$keyword}%")
                        ->orWhere('phone', 'like', "%{$keyword}%");
                });
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->whereHas('categories', function ($catQuery) use ($request) {
                    $catQuery->where('guide_category_id', $request->integer('category_id'));
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', request('status')))
            ->orderBy('full_name')
            ->paginate(10)
            ->appends($request->only(['keyword', 'category_id', 'status']));

        $categories = GuideCategory::orderBy('name')->get();

        return view('admin.guides.index', compact('guides', 'categories'));
    }

    public function create(): View
    {
        $categories = GuideCategory::orderBy('name')->get();
        return view('admin.guides.create', [
            'guide' => new Guide(),
            'categories' => $categories,
        ]);
    }

    public function store(StoreGuideRequest $request): RedirectResponse
    {
        $guide = $this->guideService->create($request->validated());

        return redirect()->route('admin.guides.index')
            ->with('success', "Đã tạo hồ sơ hướng dẫn viên {$guide->full_name}");
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

        return view('admin.guides.edit', compact('guide', 'categories'));
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

