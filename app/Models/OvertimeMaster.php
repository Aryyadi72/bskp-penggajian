<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'overtime_min',
        'overtime_max',
        'overtime_value',
        'overtime_target',
    ];
}
