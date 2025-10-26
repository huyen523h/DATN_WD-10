<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;

class InvoiceWebController extends Controller
{
    /**
     * Create invoice for booking
     */
    public function createInvoice(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'amount' => 'required|numeric|min:0',
            ]);

            $booking = Booking::findOrFail($request->booking_id);

            // Check if invoice already exists
            if ($booking->invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hóa đơn đã tồn tại cho booking này.'
                ], 400);
            }

            // Create invoice
            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'issue_date' => now(),
                'amount' => $request->amount,
                'status' => 'issued',
            ]);

            $invoice->load(['booking.tour', 'booking.user']);

            return response()->json([
                'success' => true,
                'message' => 'Hóa đơn đã được tạo thành công',
                'data' => $invoice
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tạo hóa đơn: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update invoice status
     */
    public function updateStatus(Request $request, $invoiceId): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:issued,paid,cancelled',
            ]);

            $invoice = Invoice::findOrFail($invoiceId);
            $invoice->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Trạng thái hóa đơn đã được cập nhật',
                'data' => $invoice
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get invoice details
     */
    public function show($invoiceId): JsonResponse
    {
        try {
            $invoice = Invoice::with(['booking.tour', 'booking.user', 'booking.departure'])
                             ->findOrFail($invoiceId);

            return response()->json([
                'success' => true,
                'data' => $invoice
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hóa đơn: ' . $e->getMessage()
            ], 404);
        }
    }
}
