<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'post_id', 'like_hash', 'ip_address',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
