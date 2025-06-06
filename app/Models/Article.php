<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'publish_at'
    ];

    protected $casts = [
        'publish_at' => 'datetime'
    ];

}
