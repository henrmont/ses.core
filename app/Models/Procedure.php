<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procedure extends Model
{
    use SoftDeletes;

    protected $connection = 'sigtap';

    protected $fillable = [
        'competence_id',
        'group_id',
        'subgroup_id',
        'organization_form_id',
        'financing_id',
        'heading_id',
        'code',
        'name',
        'complexity',
        'gender',
        'max_execution',
        'days_of_stay',
        'time_of_stay',
        'points',
        'min_age',
        'max_age',
        'hospital_procedure_value',
        'outpatient_procedure_value',
        'profissional_procedure_value',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function subgroup(): BelongsTo
    {
        return $this->belongsTo(Subgroup::class);
    }

    public function organization_form(): BelongsTo
    {
        return $this->belongsTo(OrganizationForm::class);
    }

    public function financing(): BelongsTo
    {
        return $this->belongsTo(Financing::class);
    }

    public function heading(): BelongsTo
    {
        return $this->belongsTo(Heading::class);
    }

    public function description(): HasOne
    {
        return $this->hasOne(ProcedureDescription::class);
    }

    public function modalities(): BelongsToMany
    {
        return $this->belongsToMany(Modality::class, 'procedure_modalities');
    }

    public function cids(): BelongsToMany
    {
        return $this->belongsToMany(Cid::class, 'procedure_cids');
    }

    public function bed_types(): BelongsToMany
    {
        return $this->belongsToMany(BedType::class, 'procedure_bed_types');
    }

    public function conditioned_rules(): BelongsToMany
    {
        return $this->belongsToMany(ConditionedRule::class, 'procedure_conditioned_rules');
    }

    public function details(): BelongsToMany
    {
        return $this->belongsToMany(Detail::class, 'procedure_details');
    }

    public function licenses(): BelongsToMany
    {
        return $this->belongsToMany(License::class, 'procedure_licenses');
    }

    public function license_groups(): BelongsToMany
    {
        return $this->belongsToMany(LicenseGroup::class, 'procedure_licenses');
    }

    public function network_components(): BelongsToMany
    {
        return $this->belongsToMany(NetworkComponent::class, 'procedure_network_components');
    }

    public function ocupations(): BelongsToMany
    {
        return $this->belongsToMany(Ocupation::class, 'procedure_ocupations');
    }

    public function registers(): BelongsToMany
    {
        return $this->belongsToMany(Register::class, 'procedure_registers');
    }

    public function renasess(): BelongsToMany
    {
        return $this->belongsToMany(Renases::class, 'procedure_renasess');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'procedure_services');
    }

    public function sia_sihs(): BelongsToMany
    {
        return $this->belongsToMany(SiaSih::class, 'procedure_sia_sihs')->withPivot('type');
    }

    public function tusses(): BelongsToMany
    {
        return $this->belongsToMany(Tuss::class, 'procedure_tusses');
    }

    public function compatibles(): HasMany
    {
        return $this->hasMany(ProcedureCompatible::class);
    }

    public function increments(): HasMany
    {
        return $this->hasMany(ProcedureIncrement::class);
    }

    public function origin(): BelongsToMany
    {
        return $this->belongsToMany(Procedure::class, 'procedure_origins', 'procedure_id', 'origin_procedure_id');
    }

    public function exception(): HasOne
    {
        return $this->hasOne(ProcedureException::class);
    }

}
