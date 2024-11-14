<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';
    protected $fillable = [
        'title',
        'path',
        'description',
    ];
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $hidden = ['created_at', 'updated_at'];

    protected static function booted(){
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->enable = true;
        });
    }
}
