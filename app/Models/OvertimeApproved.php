<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeApproved extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'hour_og',
        'hour_call',
        'overtime_call',
        'overtime_date',
        'approved_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'nik');
    }
}
