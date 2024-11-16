<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Interaction extends Model
{
    use HasFactory;
    protected $table = 'interactions';
    protected $fillable = [
        'object_a_id',
        'object_b_id',
        'object_a_type',
        'object_b_type',
        'interaction_type'
    ];

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted(){
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
