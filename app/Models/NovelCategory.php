<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NovelCategory extends Model
{
    use HasFactory;
    protected $table = 'novels_categories';
    protected $fillable = [
        'novel_id',
        'category_id',
        'novel_type'
    ];

    protected static function booted()
    {
        parent::boot();
        static::addGlobalScope(function ($query) {
            $query->with('category');
        });
    }

    public function novel(){
        return $this->morphTo();
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
