<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'url',
        'icon',
    ];

    public function module(): HasOne
    {
        return $this->hasOne(ModuleUser::class);
    }
}
