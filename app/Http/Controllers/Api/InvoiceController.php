<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Get invoice by booking ID
     */
    public function show(Request $request, $bookingId): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Find booking with invoice
            $booking = Booking::with([
                'invoice',
                'tour',
                'user',
                'departure',
                'promotion'
            ])->findOrFail($bookingId);

            // Check if user has permission to view this invoice
            if (!$user->isAdmin() && !$user->isStaff() && $booking->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You can only view your own invoices.'
                ], 403);
            }

            // Check if invoice exists
            if (!$booking->invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found for this booking.'
                ], 404);
            }

            $invoice = $booking->invoice;
            $invoice->load('booking.tour', 'booking.user', 'booking.departure', 'booking.promotion');

            return response()->json([
                'success' => true,
                'data' => [
                    'invoice' => $invoice,
                    'booking' => $booking,
                    'tour' => $booking->tour,
                    'user' => $booking->user,
                    'departure' => $booking->departure,
                    'promotion' => $booking->promotion,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF invoice
     */
    public function generatePdf(Request $request, $bookingId): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Find booking with invoice
            $booking = Booking::with([
                'invoice',
                'tour',
                'user',
                'departure',
                'promotion'
            ])->findOrFail($bookingId);

            // Check if user has permission to view this invoice
            if (!$user->isAdmin() && !$user->isStaff() && $booking->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You can only generate invoices for your own bookings.'
                ], 403);
            }

            // Check if invoice exists
            if (!$booking->invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found for this booking.'
                ], 404);
            }

            $invoice = $booking->invoice;

            // Prepare data for PDF
            $data = [
                'invoice' => $invoice,
                'booking' => $booking,
                'tour' => $booking->tour,
                'user' => $booking->user,
                'departure' => $booking->departure,
                'promotion' => $booking->promotion,
                'company' => [
                    'name' => 'Tour365',
                    'address' => '123 Đường ABC, Quận 1, TP.HCM',
                    'phone' => '0901234567',
                    'email' => 'info@tour365.vn',
                    'website' => 'www.tour365.vn',
                    'tax_code' => '0123456789',
                ]
            ];

            // Generate PDF
            $pdf = Pdf::loadView('invoices.pdf', $data);
            $pdf->setPaper('A4', 'portrait');

            // Generate filename
            $filename = 'invoice_' . $invoice->invoice_number . '_' . now()->format('YmdHis') . '.pdf';

            // Store PDF temporarily
            $pdfPath = 'temp/invoices/' . $filename;
            Storage::disk('public')->put($pdfPath, $pdf->output());

            return response()->json([
                'success' => true,
                'message' => 'PDF generated successfully',
                'data' => [
                    'filename' => $filename,
                    'download_url' => Storage::disk('public')->url($pdfPath),
                    'invoice_number' => $invoice->invoice_number,
                    'booking_id' => $booking->id,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download PDF invoice
     */
    public function downloadPdf(Request $request, $bookingId)
    {
        try {
            $user = $request->user();
            
            // Find booking with invoice
            $booking = Booking::with([
                'invoice',
                'tour',
                'user',
                'departure',
                'promotion'
            ])->findOrFail($bookingId);

            // Check if user has permission to view this invoice
            if (!$user->isAdmin() && !$user->isStaff() && $booking->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You can only download invoices for your own bookings.'
                ], 403);
            }

            // Check if invoice exists
            if (!$booking->invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found for this booking.'
                ], 404);
            }

            $invoice = $booking->invoice;

            // Prepare data for PDF
            $data = [
                'invoice' => $invoice,
                'booking' => $booking,
                'tour' => $booking->tour,
                'user' => $booking->user,
                'departure' => $booking->departure,
                'promotion' => $booking->promotion,
                'company' => [
                    'name' => 'Tour365',
                    'address' => '123 Đường ABC, Quận 1, TP.HCM',
                    'phone' => '0901234567',
                    'email' => 'info@tour365.vn',
                    'website' => 'www.tour365.vn',
                    'tax_code' => '0123456789',
                ]
            ];

            // Generate PDF
            $pdf = Pdf::loadView('invoices.pdf', $data);
            $pdf->setPaper('A4', 'portrait');

            // Generate filename
            $filename = 'invoice_' . $invoice->invoice_number . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all invoices for authenticated user (Customer) or all invoices (Admin/Staff)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $query = Invoice::with(['booking.tour', 'booking.user', 'booking.departure']);

            // If user is customer, only show their invoices
            if ($user->isCustomer()) {
                $query->whereHas('booking', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('issue_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('issue_date', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                      ->orWhereHas('booking.tour', function ($tourQuery) use ($search) {
                          $tourQuery->where('title', 'like', "%{$search}%");
                      })
                      ->orWhereHas('booking.user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }

            $invoices = $query->orderBy('issue_date', 'desc')
                            ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $invoices
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve invoices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create invoice for booking (Admin/Staff only)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Only admin and staff can create invoices
            if (!$user->isAdmin() && !$user->isStaff()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only admin and staff can create invoices.'
                ], 403);
            }

            $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'amount' => 'required|numeric|min:0',
            ]);

            $booking = Booking::findOrFail($request->booking_id);

            // Check if invoice already exists
            if ($booking->invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice already exists for this booking.'
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

            $invoice->load(['booking.tour', 'booking.user', 'booking.departure']);

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'data' => $invoice
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update invoice status (Admin/Staff only)
     */
    public function update(Request $request, $invoiceId): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Only admin and staff can update invoices
            if (!$user->isAdmin() && !$user->isStaff()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only admin and staff can update invoices.'
                ], 403);
            }

            $request->validate([
                'status' => 'required|in:issued,paid,cancelled',
                'amount' => 'sometimes|numeric|min:0',
            ]);

            $invoice = Invoice::findOrFail($invoiceId);
            $invoice->update($request->only(['status', 'amount']));

            $invoice->load(['booking.tour', 'booking.user', 'booking.departure']);

            return response()->json([
                'success' => true,
                'message' => 'Invoice updated successfully',
                'data' => $invoice
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
