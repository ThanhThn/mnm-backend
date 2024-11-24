<?php

namespace App\Models;

use App\Support\Interaction\InteractionSupport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Comics extends Model
{
    use HasFactory;
    protected $table="comics";
    protected $fillable=[
        "api_id",
        "name",
        "slug",
        "thumbnail"
    ];
    protected $appends = [
        'likes'
    ];

    protected $hidden = ["created_at"];
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;

    protected static function boot(){
        parent::boot();
        static::creating(function ($query) {
            if(empty($query->id)){
                $query->id = (string) Str::uuid();
                $query->status = 1;
            }
        });
    }

    public function getLikesAttribute(){
        return InteractionSupport::countInteraction(1, $this->id, 3);
    }

    public function information()
    {
        return $this->morphMany(NovelCategory::class, 'novel')->where('status', '!=', 0);
    }
}
