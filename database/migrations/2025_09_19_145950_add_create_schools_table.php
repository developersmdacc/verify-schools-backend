<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('nat_emis')->unique()->comment('National EMIS number');
            $table->year('data_year')->nullable();
            $table->string('province')->nullable();
            $table->string('province_cd')->nullable();
            $table->string('official_institution_name')->nullable();
            $table->string('status')->nullable();
            $table->string('sector')->nullable();
            $table->string('type_doe')->nullable();
            $table->string('phase_ped')->nullable();
            $table->string('specialisation')->nullable();
            $table->boolean('full_service_school')->nullable();
            $table->string('ei_district')->nullable();
            $table->string('ei_circuit')->nullable();
            $table->string('owner_land')->nullable();
            $table->string('owner_buildings')->nullable();
            $table->string('ex_dept')->nullable();
            $table->string('persal_paypoint_no')->nullable();
            $table->string('persal_component_no')->nullable();
            $table->string('exam_no')->nullable();
            $table->string('exam_centre')->nullable();
            $table->decimal('gis_longitude', 10, 6)->nullable();
            $table->decimal('gis_latitude', 10, 6)->nullable();
            $table->string('dmun_name')->nullable();
            $table->string('lmun_name')->nullable();
            $table->string('ward_id')->nullable();
            $table->string('sp_code')->nullable();
            $table->string('sp_name')->nullable();
            $table->string('addressee')->nullable();
            $table->string('township_village')->nullable();
            $table->string('suburb')->nullable();
            $table->string('town_city')->nullable();
            $table->string('street_address')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('telephone')->nullable();
            $table->boolean('section21')->nullable();
            $table->string('section21_function')->nullable();
            $table->tinyInteger('quintile')->nullable();
            $table->string('nas')->nullable();
            $table->string('nodal_area')->nullable();
            $table->date('registration_date')->nullable();
            $table->boolean('no_fee_school')->nullable();
            $table->string('urban_rural')->nullable();
            $table->string('allocation')->nullable();
            $table->string('demarcation_from')->nullable();
            $table->string('demarcation_to')->nullable();
            $table->string('old_nat_emis')->nullable();
            $table->string('new_nat_emis')->nullable();
            $table->integer('learners_2024')->nullable();
            $table->integer('educators_2024')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
