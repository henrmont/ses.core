<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcedureException extends Model
{
    use SoftDeletes;

    protected $connection = 'sigtap';

    protected $fillable = [
        'restriction_procedure_id',
        'procedure_id',
        'register_id',
        'compatible_procedure_id',
        'compatible_register_id',
        'type',
    ];

    public function restriction_procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class, 'restriction_procedure_id');
    }

    public function compatible_procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class, 'compatible_procedure_id');
    }

    public function register(): BelongsTo
    {
        return $this->belongsTo(Register::class);
    }

    public function compatible_register(): BelongsTo
    {
        return $this->belongsTo(Register::class, 'compatible_register_id');
    }






}
