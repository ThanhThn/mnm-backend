<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chapter extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'chapters';
    protected $fillable = [
        'title',
        'slug',
        'content',
        'story_id',
        'sound',
        'status',
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
