<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiLeadEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citi_lead_entries', function (Blueprint $table) {
            $table->id();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('card_type')->nullable();
            $table->string('sarrogate')->nullable();
            $table->string('mobile')->max(10)->nullable();
            $table->string('pan')->nullable();
            $table->string('dob')->nullable();
            $table->string('education')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('marital_status')->nullable();
            $table->longText('resi_address')->nullable();
            $table->string('resi_city')->nullable();
            $table->string('resi_pin')->nullable();
            $table->string('curr_adrs_proof')->nullable();
            $table->string('resi_phone')->nullable();
            $table->string('sbi_ac')->nullable();
            $table->string('email')->nullable();
            $table->string('occupation')->nullable();
            $table->string('designation')->nullable();
            $table->string('company')->nullable();
            $table->longText('office_address')->nullable();
            $table->string('office_city')->nullable();
            $table->string('office_pin')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('aadhaar_linked_mobile')->nullable();
            $table->string('appointment_date')->nullable();
            $table->string('appointment_time')->nullable();
            $table->string('card_applied')->nullable();
            $table->string('appointment_adrs')->nullable();
            $table->string('tl_status')->nullable();
            $table->longText('bank_document')->nullable();
            $table->longText('salary_slip')->nullable();
            $table->longText('pan_card')->nullable();
            $table->longText('aadhar_card')->nullable();
            $table->string('tc_id')->nullable();
            $table->string('tl_id')->nullable();
            $table->string('bm_id')->nullable();
            $table->boolean('pan_check')->default(0)->nullable();
            $table->boolean('tl_approve')->default(0)->nullable();
            $table->integer('status')->nullable();
            $table->string('comment')->nullable();
            $table->string('application_no')->nullable();
            $table->string('lead_ref')->nullable();
            $table->string('bank_remark')->nullable();
            $table->string('card_limit')->nullable();
            $table->boolean('app_code_status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citi_lead_entries');
    }
}
