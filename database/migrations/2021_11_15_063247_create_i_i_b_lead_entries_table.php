<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIIBLeadEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('i_i_b_lead_entries', function (Blueprint $table) {
            $table->id();
            $table->string('salutation')->nullable();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('card_type')->nullable();
            $table->string('mobile')->max(10)->nullable();
            $table->string('pan')->nullable();
            $table->string('dob')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('aadhaar')->nullable();
            $table->string('aadhaar_linked_mobile')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('dependent')->nullable();
            $table->longText('resi_address')->nullable();
            $table->string('resi_city')->nullable();
            $table->string('resi_pin')->nullable();
            $table->string('resi_status')->nullable();
            $table->string('current_rest_time')->nullable();
            $table->string('email')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('company')->nullable();
            $table->string('designation')->nullable();
            $table->string('current_company_experience')->nullable();
            $table->string('total_experience')->nullable();
            $table->string('office_email')->nullable();
            $table->string('pf')->nullable();
            $table->longText('office_address')->nullable();
            $table->string('office_city')->nullable();
            $table->string('office_pin')->nullable();
            $table->string('office_landline')->nullable();
            $table->longText('comm_address')->nullable();
            $table->string('nature_of_bussiness')->nullable();
            $table->string('industry')->nullable();

            $table->string('tl_status')->nullable();
            $table->longText('bank_document')->nullable();
            $table->longText('salary_slip')->nullable();
            $table->longText('pan_card')->nullable();
            $table->longText('aadhar_card')->nullable();
            $table->string('tc_id')->nullable();
            $table->string('tl_id')->nullable();
            $table->string('bm_id')->nullable();
            $table->boolean('pan_check')->default(0)->nullable();
            $table->integer('status')->nullable();
            $table->string('comment')->nullable();
            $table->string('application_no')->nullable();
            $table->string('lead_ref')->nullable();
            $table->string('bank_remark')->nullable();
            $table->string('card_limit')->nullable();
            $table->boolean('app_code_status')->default(0)->nullable();
            // $table->string('approved_by')
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
        Schema::dropIfExists('i_i_b_lead_entries');
    }
}
