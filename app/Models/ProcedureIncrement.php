<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcedureIncrement extends Model
{
    use SoftDeletes;

    protected $connection = 'sigtap';

    protected $fillable = [
        'procedure_id',
        'license_id',
        'hospital_percentage_procedure_value',
        'outpatient_percentage_procedure_value',
        'profissional_percentage_procedure_value',
    ];

    public function license()
    {
        return $this->belongsTo(License::class);
    }

}
