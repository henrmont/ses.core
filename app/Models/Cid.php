<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cid extends Model
{
    use SoftDeletes;

    protected $connection = 'sigtap';

    protected $fillable = [
        'competence_id',
        'code',
        'name',
        'grievance',
        'gender',
        'stadium',
        'irradiated_fields',
    ];

}
