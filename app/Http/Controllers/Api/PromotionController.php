<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class PromotionController extends Controller
{
    /**
     * Get all active promotions
     */
    public function index(): JsonResponse
    {
        $promotions = Promotion::where('status', 'active')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $promotions,
            'message' => 'Danh sách mã giảm giá'
        ]);
    }

    /**
     * Get promotion by code
     */
    public function show(string $code): JsonResponse
    {
        $promotion = Promotion::where('code', strtoupper($code))
            ->where('status', 'active')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $promotion,
            'message' => 'Thông tin mã giảm giá'
        ]);
    }

    /**
     * Validate promotion code
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'amount' => 'nullable|numeric|min:0'
        ]);

        $code = strtoupper($request->code);
        $amount = $request->amount ?? 0;

        $promotion = Promotion::where('code', $code)
            ->where('status', 'active')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn'
            ], 404);
        }

        $discountAmount = $promotion->calculateDiscount($amount);
        $finalAmount = $amount - $discountAmount;

        return response()->json([
            'success' => true,
            'data' => [
                'promotion' => $promotion,
                'original_amount' => $amount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount
            ],
            'message' => 'Mã giảm giá hợp lệ'
        ]);
    }

    /**
     * Get all promotions (admin only)
     */
    public function adminIndex(): JsonResponse
    {
        $promotions = Promotion::orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $promotions,
            'message' => 'Danh sách tất cả mã giảm giá'
        ]);
    }

    /**
     * Create new promotion (admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code',
            'description' => 'nullable|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        if (empty($validated['discount_percent']) && empty($validated['discount_amount'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cần nhập phần trăm hoặc số tiền giảm'
            ], 422);
        }

        $validated['code'] = strtoupper($validated['code']);

        $promotion = Promotion::create($validated);

        return response()->json([
            'success' => true,
            'data' => $promotion,
            'message' => 'Mã giảm giá đã được tạo thành công'
        ], 201);
    }

    /**
     * Update promotion (admin only)
     */
    public function update(Request $request, Promotion $promotion): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'description' => 'nullable|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        if (empty($validated['discount_percent']) && empty($validated['discount_amount'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cần nhập phần trăm hoặc số tiền giảm'
            ], 422);
        }

        $validated['code'] = strtoupper($validated['code']);

        $promotion->update($validated);

        return response()->json([
            'success' => true,
            'data' => $promotion,
            'message' => 'Mã giảm giá đã được cập nhật thành công'
        ]);
    }

    /**
     * Delete promotion (admin only)
     */
    public function destroy(Promotion $promotion): JsonResponse
    {
        $promotion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mã giảm giá đã được xóa thành công'
        ]);
    }
}
