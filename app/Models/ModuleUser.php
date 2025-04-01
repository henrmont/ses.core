<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleUser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'module_id',
    ];

    /**
     * Get the module associated with the ModuleUser.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

}
