<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupTourRequest extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'name', 'email', 'phone', 'organization',
        'destination', 'departure_date', 'duration',
        'adults', 'children', 'infants', 'budget',
        'services', 'note', 'status', 'admin_notes'
    ];

    protected $casts = [
        'departure_date' => 'date',
        'services' => 'array', // Tự động chuyển JSON sang Array
    ];
}