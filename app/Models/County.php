<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class County extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ibge_code',
        'name',
        'fu',
        'tcu_population_base_year',
        'population',
        'health_region_id',
        'macroregion',
        'polemunicipality',
        'distance_from_pole_municipality',
        'distance_from_the_capital',
        'img_map'
    ];

    public function health_region(): BelongsTo
    {
        return $this->belongsTo(HealthRegion::class);
    }
}
