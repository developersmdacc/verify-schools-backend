<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'nat_emis',
        'data_year',
        'province',
        'province_cd',
        'official_institution_name',
        'status',
        'sector',
        'type_doe',
        'phase_ped',
        'specialisation',
        'full_service_school',
        'ei_district',
        'ei_circuit',
        'owner_land',
        'owner_buildings',
        'ex_dept',
        'persal_paypoint_no',
        'persal_component_no',
        'exam_no',
        'exam_centre',
        'gis_longitude',
        'gis_latitude',
        'dmun_name',
        'lmun_name',
        'ward_id',
        'sp_code',
        'sp_name',
        'addressee',
        'township_village',
        'suburb',
        'town_city',
        'street_address',
        'postal_address',
        'telephone',
        'section21',
        'section21_function',
        'quintile',
        'nas',
        'nodal_area',
        'registration_date',
        'no_fee_school',
        'urban_rural',
        'allocation',
        'demarcation_from',
        'demarcation_to',
        'old_nat_emis',
        'new_nat_emis',
        'learners_2024',
        'educators_2024',
    ];

    /**
     * Accessor for verified status
     * Returns true if school is officially registered
     */
    public function getIsVerifiedAttribute()
    {
        return strtolower($this->status) === 'registered';
    }
}
