<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detail extends Model
{
    use SoftDeletes;

    protected $connection = 'sigtap';

    protected $fillable = [
        'competence_id',
        'code',
        'name',
    ];

    public function description(): HasOne
    {
        return $this->hasOne(DetailDescription::class);
    }

}
