<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable =[
        'customers_id',
        'description',
        'price',
        'is_paid',
        'deposit',
        'created_at',
        'updated_at',
        'fully_paid_date',
    ];
}
