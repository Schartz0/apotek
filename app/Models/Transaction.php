<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_no',
        'channel',
        'created_at_manual',
        'created_by',
        'client_name',
        'age',
        'occupation',
        'sex',
        'product_type',
        'product_id',
        'product_name',
        'qty',
        'price',
        'duration',
        'scheduled_date',
        'scheduled_time',
        'staff_nik',
        'location',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'string', // Ubah ke string agar tidak error konversi waktu
        'status' => 'string',
        'price' => 'decimal:2',
        'qty' => 'integer',
    ];

    // Relasi ke Staff
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_nik', 'nik');
    }

    // Accessor subtotal otomatis
    public function getSubtotalAttribute()
    {
        return $this->qty * $this->price;
    }
}
