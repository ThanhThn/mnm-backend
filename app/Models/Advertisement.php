<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Advertisement extends Model
{
    use HasFactory;
    protected $table = 'advertisements';
    protected $fillable = [
        'link',
        'picture_id'
    ];
    protected $casts = [
      'created_at' => 'datetime:H:i:s d/m/Y',
    ];

    protected $primaryKey = 'id';
    public $incrementing = false;
    public function picture(){
        return $this->belongsTo(Image::class);
    }

    protected static function booted(){
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
        static::addGlobalScope(function (Builder $builder) {
            $builder->with('picture');
        });
    }
}
