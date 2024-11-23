<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $fillable = [
        'user_id',
        'order_id',
        'action',
        'signature',
        'status',
    ];

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted(){
        parent::boot();
        static::creating(function ($query) {
            $query->id = Str::uuid();
        });
    }
}
