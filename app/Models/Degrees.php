<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Degrees extends Model
{
    use HasFactory;

    protected $fillable =[
        'customers_id',
        'left_eye_degree',
        'right_eye_degree',
    ];
}
