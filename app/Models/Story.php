<?php

namespace App\Models;

use App\Support\Interaction\InteractionSupport;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Story extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'stories';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'author_id',
        'thumbnail_id',
        'status',
    ];
    protected $appends = [
        'likes'
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
        static::addGlobalScope(function (Builder $builder) {
            $builder->with('storyPicture');
        });
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function storyPicture()
    {
        return $this->belongsTo(Image::class, 'thumbnail_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'novels_categories', 'novel_id', 'category_id')->withPivot('novel_type')->withTimestamps();
    }

    public function getLikesAttribute()
    {
        return InteractionSupport::countInteraction(1, $this->id, 2);
    }
}
