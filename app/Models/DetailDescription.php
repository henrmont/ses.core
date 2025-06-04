<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailDescription extends Model
{
    use SoftDeletes;

    protected $connection = 'sigtap';

    protected $fillable = [
        'competence_id',
        'detail_id',
        'description',
    ];
}
