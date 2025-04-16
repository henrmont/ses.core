<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRegion extends Model
{
    protected $fillable = [
        'name',
        'ibge_code',
    ];
}
