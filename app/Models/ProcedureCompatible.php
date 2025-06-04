<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcedureCompatible extends Model
{
    use SoftDeletes;

    protected $connection = 'sigtap';

    protected $fillable = [
        'procedure_id',
        'register_id',
        'compatible_procedure_id',
        'compatible_register_id',
        'type',
        'amount',
    ];

    public function compatible_procedure()
    {
        return $this->belongsTo(Procedure::class, 'compatible_procedure_id');
    }

    public function register()
    {
        return $this->belongsTo(Register::class);
    }

    public function compatible_register()
    {
        return $this->belongsTo(Register::class, 'compatible_register_id');
    }

}
