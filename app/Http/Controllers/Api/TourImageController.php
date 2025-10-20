<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTourImagesRequest;
use App\Http\Requests\Api\UpdateTourImageRequest;
use App\Http\Requests\Api\ReplaceTourImagesRequest;
use App\Models\Tour;
use App\Models\TourImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TourImageController extends Controller
{
    // =============== HELPERS (NEW) ===============
    /** Trả về 'tours/xxx.jpg' từ '/storage/tours/xxx.jpg' */
    private function urlToDiskPath(string $imageUrl): string
    {
        // image_url đang lưu dạng /storage/....
        return str_replace('/storage/', '', ltrim($imageUrl, '/'));
    }

    private function deletePhysicalFile(?string $imageUrl): void
    {
        if (!$imageUrl) return;
        $rel = $this->urlToDiskPath($imageUrl);
        if ($rel) {
            Storage::disk('public')->delete($rel);
        }
    }

    // =============== API ===============

    /**
     * POST /api/tours/{tour}/images
     * Upload 1..n ảnh, append vào cuối danh sách ảnh hiện có
     */
    public function store(StoreTourImagesRequest $request, Tour $tour): JsonResponse
    {
        $uploaded = [];
        $startOrder = (int)($tour->images()->max('sort_order') ?? 0);

        foreach ($request->file('images') as $idx => $file) {
            $path = $file->store('tours', 'public');                          // lưu file
            $url  = Storage::url($path);                                      // -> /storage/...

            $img = TourImage::create([
                'tour_id'    => $tour->id,
                'image_url'  => $url,
                'is_cover'   => $tour->images()->where('is_cover', true)->exists() ? false : ($idx === 0), // đặt cover cho ảnh đầu nếu chưa có
                'sort_order' => $startOrder + $idx + 1,
            ]);

            $uploaded[] = $img;
        }

        return response()->json([
            'success' => true,
            'message' => 'Upload ảnh thành công.',
            'data'    => $uploaded,
        ], 201);
    }

    /**
     * PUT /api/tours/{tour}/images/replace
     * Transaction: xóa toàn bộ ảnh cũ + upload set mới
     */
    public function replaceAll(ReplaceTourImagesRequest $request, Tour $tour): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Xóa file vật lý & bản ghi cũ
            foreach ($tour->images as $old) {
                $this->deletePhysicalFile($old->image_url);
            }
            $tour->images()->delete();

            // Thêm mới
            $created = [];
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('tours', 'public');
                $url  = Storage::url($path);

                $created[] = TourImage::create([
                    'tour_id'    => $tour->id,
                    'image_url'  => $url,
                    'is_cover'   => $i === 0,         // đặt ảnh đầu làm cover
                    'sort_order' => $i + 1,
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Thay toàn bộ ảnh thành công.',
                'data'    => $created,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Không thể thay ảnh.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PATCH /api/tour-images/{image}
     * Cập nhật is_cover / sort_order cho 1 ảnh
     * - Nếu set is_cover = true thì bỏ cover của ảnh khác
     */
    public function update(UpdateTourImageRequest $request, TourImage $image): JsonResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($image, $data) {
            if (array_key_exists('is_cover', $data) && $data['is_cover']) {
                // bỏ cover các ảnh khác
                TourImage::where('tour_id', $image->tour_id)->where('id', '!=', $image->id)
                    ->update(['is_cover' => false]);
                $image->is_cover = true;
            }

            if (array_key_exists('sort_order', $data)) {
                $image->sort_order = $data['sort_order'];
            }

            $image->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật ảnh thành công.',
            'data'    => $image,
        ]);
    }

    /**
     * DELETE /api/tour-images/{image}
     * Xóa 1 ảnh (bản ghi + file vật lý). Nếu ảnh đang là cover, sẽ
     * chuyển cover cho ảnh có sort_order nhỏ nhất còn lại.
     */
    public function destroy(TourImage $image): JsonResponse
    {
        DB::beginTransaction();
        try {
            $tourId   = $image->tour_id;
            $wasCover = $image->is_cover;

            // Xóa file vật lý
            $this->deletePhysicalFile($image->image_url);
            // Xóa DB
            $image->delete();

            if ($wasCover) {
                $next = TourImage::where('tour_id', $tourId)->orderBy('sort_order')->first();
                if ($next) {
                    $next->is_cover = true;
                    $next->save();
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Đã xóa ảnh.']);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa ảnh.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
