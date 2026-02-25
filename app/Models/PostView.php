<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'post_id', 'view_hash', 'ip_address',
        'device_type', 'browser', 'os', 'country_code',
        'referrer_domain', 'referrer_url',
        'utm_source', 'utm_medium', 'utm_campaign',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
