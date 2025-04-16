<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
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

    public function health_region()
    {
        return $this->belongsTo(HealthRegion::class);
    }
}
