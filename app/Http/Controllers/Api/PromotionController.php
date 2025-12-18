<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PromotionController extends Controller
{
    /**
     * Get all active promotions
     */
    public function index()
    {
        try {
            $promotions = Promotion::where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->select('id', 'code', 'name', 'description', 'discount_type', 'discount_value', 'start_date', 'end_date')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $promotions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get promotions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get promotion by code
     */
    public function show($code)
    {
        try {
            $promotion = Promotion::where('code', $code)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if (!$promotion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Promotion not found or expired'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $promotion
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get promotion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate promotion code
     */
    public function validate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string',
                'total_amount' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $promotion = Promotion::where('code', $request->code)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if (!$promotion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired promotion code'
                ], 404);
            }

            // Check minimum order amount
            if ($request->total_amount < $promotion->min_order_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum order amount not met',
                    'minimum_amount' => $promotion->min_order_amount
                ], 400);
            }

            // Calculate discount
            $discount = 0;
            if ($promotion->discount_type === 'percentage') {
                $discount = ($request->total_amount * $promotion->discount_value) / 100;
                if ($promotion->max_discount && $discount > $promotion->max_discount) {
                    $discount = $promotion->max_discount;
                }
            } else {
                $discount = $promotion->discount_value;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'promotion' => $promotion,
                    'discount_amount' => $discount,
                    'final_amount' => $request->total_amount - $discount
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate promotion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all promotions (Admin only)
     */
    public function adminIndex(Request $request)
    {
        try {
            if ($request->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }

            $promotions = Promotion::orderBy('id', 'desc')->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $promotions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get promotions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create promotion (Admin only)
     */
    public function store(Request $request)
    {
        try {
            if ($request->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'code' => 'required|string|unique:promotions,code',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'discount_type' => 'required|in:percentage,fixed',
                'discount_value' => 'required|numeric|min:0',
                'min_order_amount' => 'nullable|numeric|min:0',
                'max_discount' => 'nullable|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $promotion = Promotion::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Promotion created successfully',
                'data' => $promotion
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create promotion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update promotion (Admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            if ($request->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }

            $promotion = Promotion::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'code' => 'sometimes|string|unique:promotions,code,' . $id,
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'discount_type' => 'sometimes|in:percentage,fixed',
                'discount_value' => 'sometimes|numeric|min:0',
                'min_order_amount' => 'nullable|numeric|min:0',
                'max_discount' => 'nullable|numeric|min:0',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after:start_date',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $promotion->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Promotion updated successfully',
                'data' => $promotion
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update promotion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete promotion (Admin only)
     */
    public function destroy(Request $request, $id)
    {
        try {
            if ($request->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }

            $promotion = Promotion::findOrFail($id);
            $promotion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Promotion deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete promotion',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
