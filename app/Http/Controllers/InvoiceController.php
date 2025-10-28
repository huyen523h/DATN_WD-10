<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['booking', 'user', 'tour']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('tour_title', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice
     */
    public function create()
    {
        $bookings = Booking::with(['user', 'tour'])
                          ->where('status', 'confirmed')
                          ->whereDoesntHave('invoice')
                          ->get();

        return view('admin.invoices.create', compact('bookings'));
    }

    /**
     * Store a newly created invoice
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_tax_code' => 'nullable|string|max:50',
            'adult_price' => 'required|numeric|min:0',
            'child_price' => 'required|numeric|min:0',
            'infant_price' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $booking = Booking::with(['user', 'tour'])->findOrFail($request->booking_id);

        $invoice = new Invoice();
        $invoice->invoice_number = $invoice->generateInvoiceNumber();
        $invoice->booking_id = $booking->id;
        $invoice->user_id = $booking->user_id;
        $invoice->tour_id = $booking->tour_id;
        
        // Company Information
        $invoice->company_name = $request->company_name;
        $invoice->company_address = $request->company_address;
        $invoice->company_phone = $request->company_phone;
        $invoice->company_email = $request->company_email;
        $invoice->company_tax_code = $request->company_tax_code;
        
        // Customer Information
        $invoice->customer_name = $booking->user->name;
        $invoice->customer_email = $booking->user->email;
        $invoice->customer_phone = $booking->user->phone ?? '';
        $invoice->customer_address = $booking->user->address ?? '';
        
        // Tour Information
        $invoice->tour_title = $booking->tour->title;
        $invoice->departure_date = $booking->departure->departure_date ?? now()->addDays(7);
        
        // Guest Information
        $invoice->adults = $booking->adults;
        $invoice->children = $booking->children;
        $invoice->infants = $booking->infants;
        
        // Pricing
        $invoice->adult_price = $request->adult_price;
        $invoice->child_price = $request->child_price;
        $invoice->infant_price = $request->infant_price;
        $invoice->tax_rate = $request->tax_rate;
        $invoice->discount_amount = $request->discount_amount ?? 0;
        
        // Calculate totals
        $invoice->calculateTotals();
        
        // Payment Information
        $invoice->payment_method = 'bank_transfer';
        $invoice->payment_status = 'pending';
        
        // Invoice Dates
        $invoice->invoice_date = now();
        $invoice->due_date = $request->due_date;
        
        // Additional Information
        $invoice->notes = $request->notes;
        $invoice->status = 'draft';
        
        $invoice->save();

        return redirect()->route('admin.invoices.show', $invoice)
                        ->with('success', 'Hóa đơn đã được tạo thành công!');
    }

    /**
     * Display the specified invoice
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['booking', 'user', 'tour']);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the invoice
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load(['booking', 'user', 'tour']);
        return view('admin.invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified invoice
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_tax_code' => 'nullable|string|max:50',
            'adult_price' => 'required|numeric|min:0',
            'child_price' => 'required|numeric|min:0',
            'infant_price' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,sent,paid,cancelled',
            'payment_status' => 'required|in:pending,paid,cancelled,refunded',
        ]);

        // Company Information
        $invoice->company_name = $request->company_name;
        $invoice->company_address = $request->company_address;
        $invoice->company_phone = $request->company_phone;
        $invoice->company_email = $request->company_email;
        $invoice->company_tax_code = $request->company_tax_code;
        
        // Pricing
        $invoice->adult_price = $request->adult_price;
        $invoice->child_price = $request->child_price;
        $invoice->infant_price = $request->infant_price;
        $invoice->tax_rate = $request->tax_rate;
        $invoice->discount_amount = $request->discount_amount ?? 0;
        
        // Calculate totals
        $invoice->calculateTotals();
        
        // Status
        $invoice->status = $request->status;
        $invoice->payment_status = $request->payment_status;
        $invoice->due_date = $request->due_date;
        $invoice->notes = $request->notes;
        
        $invoice->save();

        return redirect()->route('admin.invoices.show', $invoice)
                        ->with('success', 'Hóa đơn đã được cập nhật thành công!');
    }

    /**
     * Generate PDF for the invoice
     */
    public function generatePdf(Invoice $invoice)
    {
        $invoice->load(['booking', 'user', 'tour']);
        
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
                  ->setPaper('A4', 'portrait')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled' => true,
                      'defaultFont' => 'DejaVu Sans'
                  ]);

        return $pdf->stream("invoice-{$invoice->invoice_number}.pdf");
    }

    /**
     * Download PDF for the invoice
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['booking', 'user', 'tour']);
        
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
                  ->setPaper('A4', 'portrait')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled' => true,
                      'defaultFont' => 'DejaVu Sans'
                  ]);

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    /**
     * Save PDF to storage
     */
    public function savePdf(Invoice $invoice)
    {
        $invoice->load(['booking', 'user', 'tour']);
        
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
                  ->setPaper('A4', 'portrait')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled' => true,
                      'defaultFont' => 'DejaVu Sans'
                  ]);

        $filename = "invoice-{$invoice->invoice_number}-" . time() . ".pdf";
        $path = "invoices/{$filename}";
        
        Storage::disk('public')->put($path, $pdf->output());
        
        $invoice->pdf_path = $filename;
        $invoice->save();

        return response()->json([
            'success' => true,
            'message' => 'PDF đã được lưu thành công!',
            'pdf_url' => $invoice->getPdfUrl()
        ]);
    }

    /**
     * Send invoice via email
     */
    public function sendEmail(Invoice $invoice)
    {
        $invoice->load(['booking', 'user', 'tour']);
        
        // Generate PDF if not exists
        if (!$invoice->pdf_path) {
            $this->savePdf($invoice);
            $invoice->refresh();
        }

        try {
            Mail::send('emails.invoice', compact('invoice'), function ($message) use ($invoice) {
                $message->to($invoice->customer_email, $invoice->customer_name)
                        ->subject("Hóa đơn #{$invoice->invoice_number} - {$invoice->tour_title}")
                        ->attach(Storage::disk('public')->path("invoices/{$invoice->pdf_path}"));
            });

            $invoice->status = 'sent';
            $invoice->save();

            return response()->json([
                'success' => true,
                'message' => 'Hóa đơn đã được gửi email thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(Invoice $invoice)
    {
        $invoice->payment_status = 'paid';
        $invoice->status = 'paid';
        $invoice->save();

        // Update booking status
        $invoice->booking->status = 'confirmed';
        $invoice->booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Hóa đơn đã được đánh dấu là đã thanh toán!'
        ]);
    }

    /**
     * Delete the invoice
     */
    public function destroy(Invoice $invoice)
    {
        // Delete PDF file if exists
        if ($invoice->pdf_path) {
            Storage::disk('public')->delete("invoices/{$invoice->pdf_path}");
        }

        $invoice->delete();

        return redirect()->route('admin.invoices.index')
                        ->with('success', 'Hóa đơn đã được xóa thành công!');
    }

    /**
     * Customer view of invoice
     */
    public function customerView($token)
    {
        $invoice = Invoice::where('invoice_number', $token)->firstOrFail();
        $invoice->load(['booking', 'user', 'tour']);
        
        return view('invoices.customer-view', compact('invoice'));
    }

    /**
     * Customer download PDF
     */
    public function customerDownloadPdf($token)
    {
        $invoice = Invoice::where('invoice_number', $token)->firstOrFail();
        return $this->downloadPdf($invoice);
    }
}
