<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SbiLeadEntry extends Model
{
    use HasFactory;
    protected $fillable=[
        'salutation',
'fname',
'lname',
        'sarrogate',
'mobile',
'pan',

'dob',
'education',
'father_name',
'mother_name',
'marital_status',
'resi_address',
'resi_city',
'resi_pin',
'curr_adrs_proof',
'resi_phone',
'sbi_ac',
'email',
'occupation',
'designation',
'company',
'office_address',
'office_city',
'office_pin',
'office_phone',
'aadhaar_linked_mobile',
'appointment_date',
'appointment_time',
'card_applied',
'appointment_adrs',
'salary_slip',
'pan_card',
'aadhar_card',
'tc_id',
'tl_id',
'bm_id',
'pan_check',
'tl_approve',
'status',
'comment',
'application_no',
'lead_ref',
'bank_remark',
'app_code_status',
'bank_document',
'card_limit',
'tl_status',
'bank_pass',
'salary_pass',
'pan_pass',
'aadhar_pass'
    ];
}
