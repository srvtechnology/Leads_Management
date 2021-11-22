<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitiLeadEntry extends Model
{
    use HasFactory;
    protected $fillable=[
'fname',
'lname',
'card_type',
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
'email',
'occupation',
'designation',
'company',
'office_address',
'office_city',
'office_pin',
'office_phone',
'aadhaar_linked_mobile',
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
'bank_remark',
'app_code_status',
'bank_document',
'card_limit',
'tl_status',
'bank_pass',
'salary_pass',
'pan_pass',
'aadhar_pass',
'other_doc',
'other_doc_pass'
    ];
}
