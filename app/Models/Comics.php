<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Comics extends Model
{
    use HasFactory;
    protected $table="comics";
    protected $fillable=[
        "api_id"
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
}
