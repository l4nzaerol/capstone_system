<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model {
    use HasFactory;

    protected $fillable = [
        'product_name',
        'date',
        'stage',
        'status',
        'quantity',
        'resources_used',
        'notes',
    ];

    protected $casts = [
        'resources_used' => 'array',
        'date' => 'date:Y-m-d',
    ];
}