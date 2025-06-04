<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthRegion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'ibge_code',
    ];
}
