<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Story extends Model
{
    use HasFactory;
    protected $table = 'stories';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'author_id',
        'thumbnail_id',
        'active_status',
        'completed_status'
    ];
    protected $primaryKey = "id";
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($query) {
            if (empty($query->id)) {
                $query->id = (string) Str::uuid();
            }
        });
    }
}
