<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Author extends Model
{
    use HasFactory;
    protected $table = 'authors';
    protected $fillable = [
        'full_name',
        'pen_name',
        'birth_date',
        'profile_picture_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public  $incrementing = false;

    protected $casts = [
        'birth_date' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function profilePicture(){
        return $this->belongsTo(Image::class, 'profile_picture_id');
    }
}