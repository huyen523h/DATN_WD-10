<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'booking_id',
        'user_id',
        'tour_id',
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'company_tax_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'tour_title',
        'departure_date',
        'adults',
        'children',
        'infants',
        'adult_price',
        'child_price',
        'infant_price',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'invoice_date',
        'due_date',
        'notes',
        'pdf_path',
        'status'
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'due_date' => 'datetime',
        'departure_date' => 'date',
        'adult_price' => 'decimal:2',
        'child_price' => 'decimal:2',
        'infant_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('payment_status', '!=', 'paid');
    }

    // Accessors & Mutators
    public function getFormattedInvoiceNumberAttribute()
    {
        return 'INV-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 0, ',', '.') . ' VNĐ';
    }

    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 0, ',', '.') . ' VNĐ';
    }

    public function getFormattedTaxAmountAttribute()
    {
        return number_format($this->tax_amount, 0, ',', '.') . ' VNĐ';
    }

    public function getFormattedDiscountAmountAttribute()
    {
        return number_format($this->discount_amount, 0, ',', '.') . ' VNĐ';
    }

    // Methods
    public function generateInvoiceNumber()
    {
        $year = now()->year;
        $month = now()->format('m');
        $lastInvoice = self::whereYear('created_at', $year)
                          ->whereMonth('created_at', $month)
                          ->orderBy('id', 'desc')
                          ->first();
        
        $sequence = $lastInvoice ? (intval(substr($lastInvoice->invoice_number, -4)) + 1) : 1;
        
        return "INV-{$year}{$month}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function calculateTotals()
    {
        $adultTotal = $this->adults * $this->adult_price;
        $childTotal = $this->children * $this->child_price;
        $infantTotal = $this->infants * $this->infant_price;
        
        $this->subtotal = $adultTotal + $childTotal + $infantTotal;
        $this->tax_amount = $this->subtotal * ($this->tax_rate / 100);
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
        
        return $this;
    }

    public function isOverdue()
    {
        return $this->due_date < now() && $this->payment_status !== 'paid';
    }

    public function getPdfUrl()
    {
        if ($this->pdf_path) {
            return asset('storage/invoices/' . $this->pdf_path);
        }
        return null;
    }
}