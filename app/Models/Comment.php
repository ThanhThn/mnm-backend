<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $fillable = [
        'user_id',
        'novel_id',
        'novel_type',
        'parent_comment_id',
        'content'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected static function booted(){
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
        static::addGlobalScope(function (Builder $builder) {
            $builder->with(['user', 'reply']);
        });
    }

    public function reply(){
        return $this->hasMany(Comment::class, 'parent_comment_id', 'id');
    }
}
