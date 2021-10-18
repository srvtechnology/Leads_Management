<?php

namespace App\Http\Controllers;

use App\Models\SbiLeadEntry;
use App\Models\ScbLeadEntry;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LeadController extends Controller
{
    public function lead_entry_scb(Request $r)
    {
        // return response()->json(['msg'=>$r->name]);

        $validator = Validator::make($r->all(), [
            'email' => 'required|email',
            'name' => 'required|string|max:50',
            'card_type' => 'required',
            'mobile' => 'required',
            'pan' => 'required',
            'dob' => 'required',
            'birth_place' => 'required',
            'aadhaar' => 'required',
            'aadhaar_linked_mobile' => 'required',
            'mother_name' => 'required',
            'father_name' => 'required',
            'dependent' => 'required',
            'resi_address' => 'required',
            'resi_city' => 'required',
            'resi_pin' => 'required',
            'resi_status' => 'required',
            'current_rest_time' => 'required',
            'marital_status' => 'required',
            'spouse_name' => 'required',
            'company' => 'required',
            'designation' => 'required',
            'current_company_experience' => 'required',
            'total_experience' => 'required',
            'office_email' => 'required',
            'pf' => 'required',
            'office_address' => 'required',
            'office_city' => 'required',
            'office_pin' => 'required',
            'office_landline' => 'required',
            'comm_address' => 'required',
            'nature_of_bussiness' => 'required',
            'industry' => 'required',
            'tc' => 'required',

        ]);
        if ($r->role == 2) {
            ScbLeadEntry::where('id', $r->lead_id)->update([
                'tl_status' => $r->tlstatus, 'status' => $r->status,
                'comment' => $r->comment, 'application_no' => $r->application_no
            ]);

            return response()->json(['msg' => "Status Updated:)", 'flag' => 1]);
        } else {
            if ($validator->fails()) {
                //  Session::flash('msg', $validator->messages()->first());
                return response()->json(['msg' => $validator->messages()->first(), 'flag' => 0]);
            }
            try {
                $qry = Team::where("tc", $r->tc)->first();
            } catch (Exception $e) {
                return response()->json(['msg' => "Team Leader not Assigned!", 'flag' => 0]);
            }
            try {
                ScbLeadEntry::create([

                    'card_type' => $r->card_type,
                    'mobile' => $r->mobile,
                    'pan' => $r->pan,
                    'name' => $r->name,
                    'dob' => $r->dob,
                    'birth_place' => $r->birth_place,
                    'aadhaar' => $r->aadhaar,
                    'aadhaar_linked_mobile' => $r->aadhaar_linked_mobile,
                    'mother_name' => $r->mother_name,
                    'father_name' => $r->father_name,
                    'dependent' => $r->dependent,
                    'resi_address' => $r->resi_address,
                    'resi_city' => $r->resi_city,
                    'resi_pin' => $r->resi_pin,
                    'resi_status' => $r->resi_status,
                    'current_rest_time' => $r->current_rest_time,
                    'email' => $r->email,
                    'marital_status' => $r->marital_status,
                    'spouse_name' => $r->spouse_name,
                    'company' => $r->company,
                    'designation' => $r->designation,
                    'current_company_experience' => $r->current_company_experience,
                    'total_experience' => $r->total_experience,
                    'office_email' => $r->office_email,
                    'pf' => $r->pf,
                    'office_address' => $r->office_address,
                    'office_city' => $r->office_city,
                    'office_pin' => $r->office_pin,
                    'office_landline' => $r->office_landline,
                    'comm_address' => $r->comm_address,
                    'nature_of_bussiness' => $r->nature_of_bussiness,
                    'industry' => $r->industry,
                    'tc_id' => $qry->tc,
                    'tl_id' => $qry->tl,
                    'bm_id' => $qry->bm,
                    'status' => 7,
                ]);
                return response()->json(['msg' => "Lead Entry Submitted:)", 'flag' => 1]);
            } catch (Exception $e) {
                return response()->json(['msg' => $e->getMessage(), 'flag' => 0]);
            }
        }
    }

    public function showScbSummary()
    {
        $alltc = ScbLeadEntry::select(DB::raw(' scb_lead_entries.card_type, scb_lead_entries.mobile, scb_lead_entries.pan, scb_lead_entries.name, 
        scb_lead_entries.dob, scb_lead_entries.birth_place, scb_lead_entries.aadhaar, scb_lead_entries.aadhaar_linked_mobile, scb_lead_entries.mother_name, 
        scb_lead_entries.father_name, scb_lead_entries.dependent, scb_lead_entries.resi_address, scb_lead_entries.resi_city, scb_lead_entries.resi_pin, 
        scb_lead_entries.resi_status, scb_lead_entries.current_rest_time, scb_lead_entries.email, scb_lead_entries.marital_status, scb_lead_entries.spouse_name, 
        scb_lead_entries.company, scb_lead_entries.designation, scb_lead_entries.current_company_experience, scb_lead_entries.total_experience, 
        scb_lead_entries.office_email, scb_lead_entries.pf, scb_lead_entries.office_address, scb_lead_entries.office_city, scb_lead_entries.office_pin, 
        scb_lead_entries.office_landline, scb_lead_entries.comm_address, scb_lead_entries.nature_of_bussiness, scb_lead_entries.industry, scb_lead_entries.tc_id, 
        scb_lead_entries.tl_id, scb_lead_entries.bm_id, statuses.status as STATUS'))
            ->join('statuses', 'statuses.id', '=', 'scb_lead_entries.status')->where('scb_lead_entries.tl_status', "!=", null)->get();
        // $i = 0;
        if ($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
    public function showScbData()
    {
        // return response()->json(['msg'=>"hwllo"]);
        $alltc = ScbLeadEntry::select(DB::raw('scb_lead_entries.id as ID, scb_lead_entries.name as NAME, scb_lead_entries.pan as PAN,
        scb_lead_entries.tc_id as TC, scb_lead_entries.tl_id as TL, scb_lead_entries.bm_id as BM, scb_lead_entries.application_no as APPLICATION_No,
        scb_lead_entries.tl_status as TL_STATUS, scb_lead_entries.comment as REMARK, statuses.status as STATUS'))
            ->join('statuses', 'statuses.id', '=', 'scb_lead_entries.status')->get();
        $i = 0;
        if ($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
    public function getLeadScb($lead_id)
    {
        $alltc = ScbLeadEntry::where('id', $lead_id)->first();


        return response()->json(['lead' => $alltc]);
    }

    public static function save_file_scb(Request $request)
    {

        try {

            $file = $request->file;
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('/files');
            $file->move($destinationPath, $file_name);
            ScbLeadEntry::where('id', $request->id)->update(['bank_document' => $file_name]);
            return response()->json(['msg' => "File uploaded"]);
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }
    // ==============================SBI===================================================================
    public function lead_check_sbi(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'checkPan' => 'required',
            'checkName' => 'required|string',
            'tc' => 'required'
        ]);
        if ($validator->fails()) {
            //  Session::flash('msg', $validator->messages()->first());
            return response()->json(['msg' => $validator->messages()->first()]);
        }

        $qry = Team::where("tc", $r->tc)->first();
        if (empty($qry)) {
            $qry = Team::where("tl", $r->tc)->get();
            if (empty($qry)) {
                $qry = Team::where("bm", $r->tc)->first();
                if (empty($qry)) {
                    return response()->json(['msg' => "Something Went Wrong"]);
                } else {
                    $data = SbiLeadEntry::create([
                        'pan' => $r->checkPan, 'name' => $r->checkName,
                        'bm_id' => $qry->bm, 'status' => 7
                    ]);
                    return response()->json(['msg' => "Plase wait for verification!"]);
                }
            } else {
                $data = SbiLeadEntry::create([
                    'pan' => $r->checkPan, 'name' => $r->checkName,
                    'tl_id' => $qry[0]->tl, 'bm_id' => $qry[0]->bm, 'status' => 7
                ]);
                return response()->json(['msg' => "Plase wait for verification!"]);
            }
            return response()->json(['msg' => "tc mot found"]);
        } else {
            $data = SbiLeadEntry::create([
                'pan' => $r->checkPan, 'name' => $r->checkName,
                'tc_id' => $qry->tc, 'tl_id' => $qry->tl, 'bm_id' => $qry->bm, 'status' => 7
            ]);
            return response()->json(['msg' => "Plase wait for verification!"]);
        }
    }

    public function bm_check_pan(Request $r)
    {
        if ($r->code == 1) {
            $data = SbiLeadEntry::where('id', $r->lead_id)->update(['pan_check' => 1, 'app_code_status' => 1]);
            return response()->json(['msg' => "PAN verified!"]);
        } elseif ($r->code == 0) {
            $data = SbiLeadEntry::where('id', $r->lead_id)->update(['pan_check' => 2]);
            return response()->json(['msg' => "PAN Declined!"]);
        }
    }
    // public function tl_appropve_sbi(Request $r)
    // {
    //     $data = SbiLeadEntry::where('id', $r->lead_id)->update(['tl_approve' => 1]);
    //     return response()->json(['msg' => "TL Approved!"]);
    // }
    // public function tl_disappropve_sbi(Request $r)
    // {
    //     $data = SbiLeadEntry::where('id', $r->lead_id)->update(['tl_approve' => 0]);
    //     return response()->json(['msg' => "TL Disapproved!"]);
    // }
    public function app_code_sbi(Request $r)
    {
        $data = SbiLeadEntry::where('id', $r->lead_id)->update(['app_code_status' => 1]);
        return response()->json(['msg' => "App Code Received!"]);
    }
    public function lead_status_sbi(Request $r)
    {
        if ($r->status_type == 4) {
            $data = SbiLeadEntry::where('id', $r->lead_id)->update(['status' => $r->status, 'comment' => $r->comment]);
        } else {
            $data = SbiLeadEntry::where('id', $r->lead_id)->update(['status' => $r->status]);
        }
        return response()->json(['msg' => "Status Updated!"]);
    }

    public function lead_entry_sbi(Request $r)
    {
        // return response()->json(['msg'=>$r->name]);
        $validator = Validator::make($r->all(), [
            'email' => 'required|email',
            'name' => 'required|string|max:50',
            'sarrogate' => 'required',
            'mobile' => 'required',
            'pan' => 'required',
            'dob' => 'required',
            'education' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'marital_status' => 'required',
            'resi_address' => 'required',
            'resi_city' => 'required',
            'resi_pin' => 'required',
            'curr_adrs_proof' => 'required',
            'resi_phone' => 'required',
            'sbi_ac' => 'required',
            'occupation' => 'required',
            'designation' => 'required',
            'company' => 'required',
            'office_address' => 'required',
            'office_city' => 'required',
            'office_pin' => 'required',
            'office_phone' => 'required',
            'aadhaar_linked_mobile' => 'required',
            'appointment_date' => 'required',
            'appointment_time' => 'required',
            'card_applied' => 'required',
            'appointment_adrs' => 'required',

        ]);

        if ($validator->fails()) {
            //  Session::flash('msg', $validator->messages()->first());
            return response()->json(['msg' => $validator->messages()->first(), 'flag' => 0]);
        }
        // if ($r->tlstatus == 'CPV') {
        //     $check = SbiLeadEntry::where('id', $r->lead_id)->where('pan', $r->pan)->first();
        //     if ($check->bank_document == null) {
        //         return response()->json(['msg' => "Bank Statement Not Uploaded", 'flag' => 0]);
        //     }
        // }
        if ($r->tlstatus == 'Approve') {
            SbiLeadEntry::where('id', $r->lead_id)->where('pan', $r->pan)->update(['status' => 1]);
        }
        try {
            SbiLeadEntry::where('id', $r->lead_id)->where('pan', $r->pan)->update([
                'sarrogate' => $r->sarrogate,
                'mobile' => $r->mobile,
                'pan' => $r->pan,
                'name' => $r->name,
                'dob' => $r->dob,
                'education' => $r->education,
                'father_name' => $r->father_name,
                'mother_name' => $r->mother_name,
                'marital_status' => $r->marital_status,
                'resi_address' => $r->resi_address,
                'resi_city' => $r->resi_city,
                'resi_pin' => $r->resi_pin,
                'curr_adrs_proof' => $r->curr_adrs_proof,
                'resi_phone' => $r->resi_phone,
                'sbi_ac' => $r->sbi_ac,
                'email' => $r->email,
                'occupation' => $r->occupation,
                'designation' => $r->designation,
                'company' => $r->company,
                'office_address' => $r->office_address,
                'office_city' => $r->office_city,
                'office_pin' => $r->office_pin,
                'office_phone' => $r->office_phone,
                'aadhaar_linked_mobile' => $r->aadhaar_linked_mobile,
                'appointment_date' => $r->appointment_date,
                'appointment_time' => $r->appointment_time,
                'card_applied' => $r->card_applied,
                'appointment_adrs' => $r->appointment_adrs,
                'status' => $r->status,
                'comment' => $r->comment,
                'application_no' => $r->application_no,
                'lead_ref'=>$r->lead_ref,
                'bank_remark'=>$r->bank_remark,
                'tl_status' => $r->tlstatus,
            ]);
            return response()->json(['msg' => "Updated Succesfully:)", 'flag' => 1]);
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage(), 'flag' => 0]);
        }
    }

    public function showSbiDuplicate(Request $r)
    {
        $user = User::where('user_id', $r->id)->first();
        if ($user->role == 1) {
            $alltc = SbiLeadEntry::select(DB::raw('id as ID, name as NAME, pan as PAN,tc_id as TC, tl_id as TL, bm_id as BM, pan_check as STATUS '))
                ->where('bm_id', $r->id)->get();
        } elseif ($user->role == 2) {
            $alltc = SbiLeadEntry::select(DB::raw('id as ID, name as NAME, pan as PAN,tc_id as TC, tl_id as TL, bm_id as BM, pan_check as STATUS '))
                ->where('tl_id', $r->id)->get();
        } elseif ($user->role == 3) {
            $alltc = SbiLeadEntry::select(DB::raw('id as ID, name as NAME, pan as PAN,tc_id as TC, tl_id as TL, bm_id as BM, pan_check as STATUS '))
                ->where('tc_id', $r->id)->get();
        } elseif ($user->role == 4) {
            $alltc = SbiLeadEntry::select(DB::raw('id as ID, name as NAME, pan as PAN,tc_id as TC, tl_id as TL, bm_id as BM, pan_check as STATUS '))
                ->get();
        }
        $i = 0;
        foreach ($alltc as $row) {
            if ($row->STATUS == 1) {
                $alltc[$i]->STATUS = 'OK';
            } else if ($row->STATUS == 0) {
                $alltc[$i]->STATUS = 'Pending';
            } else if ($row->STATUS == 2) {
                $alltc[$i]->STATUS = 'NOK';
            }
            $i++;
        }
        if ($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
    public function showSbiSummary(Request $r)
    {
        $user = User::where('user_id', $r->id)->first();
        if ($user->role == 1) {
            $alltc = SbiLeadEntry::select(DB::raw('sbi_lead_entries.id,sbi_lead_entries.sarrogate,sbi_lead_entries.mobile,sbi_lead_entries.pan,sbi_lead_entries.name,sbi_lead_entries.dob,sbi_lead_entries.education,sbi_lead_entries.father_name,sbi_lead_entries.mother_name,sbi_lead_entries.marital_status,sbi_lead_entries.resi_address,sbi_lead_entries.resi_city,sbi_lead_entries.resi_pin,sbi_lead_entries.curr_adrs_proof,sbi_lead_entries.resi_phone,sbi_lead_entries.sbi_ac,sbi_lead_entries.email,sbi_lead_entries.occupation,sbi_lead_entries.designation,sbi_lead_entries.company,sbi_lead_entries.office_address,sbi_lead_entries.office_city,sbi_lead_entries.office_pin,sbi_lead_entries.office_phone,sbi_lead_entries.aadhaar_linked_mobile,sbi_lead_entries.appointment_date,sbi_lead_entries.appointment_time,sbi_lead_entries.card_applied,sbi_lead_entries.appointment_adrs, statuses.status as STATUS'))
            ->join('statuses', 'statuses.id', '=', 'sbi_lead_entries.status')
            ->where('bm_id', $r->id)->where('pan_check', 1)->where('tl_status', "!=", null)->get();
        } elseif ($user->role == 2) {
            $alltc = SbiLeadEntry::select(DB::raw('sbi_lead_entries.id,sbi_lead_entries.sarrogate,sbi_lead_entries.mobile,sbi_lead_entries.pan,sbi_lead_entries.name,sbi_lead_entries.dob,sbi_lead_entries.education,sbi_lead_entries.father_name,sbi_lead_entries.mother_name,sbi_lead_entries.marital_status,sbi_lead_entries.resi_address,sbi_lead_entries.resi_city,sbi_lead_entries.resi_pin,sbi_lead_entries.curr_adrs_proof,sbi_lead_entries.resi_phone,sbi_lead_entries.sbi_ac,sbi_lead_entries.email,sbi_lead_entries.occupation,sbi_lead_entries.designation,sbi_lead_entries.company,sbi_lead_entries.office_address,sbi_lead_entries.office_city,sbi_lead_entries.office_pin,sbi_lead_entries.office_phone,sbi_lead_entries.aadhaar_linked_mobile,sbi_lead_entries.appointment_date,sbi_lead_entries.appointment_time,sbi_lead_entries.card_applied,sbi_lead_entries.appointment_adrs, statuses.status as STATUS'))
            ->join('statuses', 'statuses.id', '=', 'sbi_lead_entries.status')
            ->where('tl_id', $r->id)->where('pan_check', 1)->where('tl_status', "!=", null)->get();
        } elseif ($user->role == 3) {
            $alltc = SbiLeadEntry::select(DB::raw('sbi_lead_entries.id,sbi_lead_entries.sarrogate,sbi_lead_entries.mobile,sbi_lead_entries.pan,sbi_lead_entries.name,sbi_lead_entries.dob,sbi_lead_entries.education,sbi_lead_entries.father_name,sbi_lead_entries.mother_name,sbi_lead_entries.marital_status,sbi_lead_entries.resi_address,sbi_lead_entries.resi_city,sbi_lead_entries.resi_pin,sbi_lead_entries.curr_adrs_proof,sbi_lead_entries.resi_phone,sbi_lead_entries.sbi_ac,sbi_lead_entries.email,sbi_lead_entries.occupation,sbi_lead_entries.designation,sbi_lead_entries.company,sbi_lead_entries.office_address,sbi_lead_entries.office_city,sbi_lead_entries.office_pin,sbi_lead_entries.office_phone,sbi_lead_entries.aadhaar_linked_mobile,sbi_lead_entries.appointment_date,sbi_lead_entries.appointment_time,sbi_lead_entries.card_applied,sbi_lead_entries.appointment_adrs, statuses.status as STATUS'))
            ->join('statuses', 'statuses.id', '=', 'sbi_lead_entries.status')
            ->where('tc_id', $r->id)->where('pan_check', 1)->where('tl_status', "!=", null)->get();
        } elseif ($user->role == 4) {
            $alltc = SbiLeadEntry::select(DB::raw('sbi_lead_entries.id,sbi_lead_entries.sarrogate,sbi_lead_entries.mobile,sbi_lead_entries.pan,sbi_lead_entries.name,sbi_lead_entries.dob,sbi_lead_entries.education,sbi_lead_entries.father_name,sbi_lead_entries.mother_name,sbi_lead_entries.marital_status,sbi_lead_entries.resi_address,sbi_lead_entries.resi_city,sbi_lead_entries.resi_pin,sbi_lead_entries.curr_adrs_proof,sbi_lead_entries.resi_phone,sbi_lead_entries.sbi_ac,sbi_lead_entries.email,sbi_lead_entries.occupation,sbi_lead_entries.designation,sbi_lead_entries.company,sbi_lead_entries.office_address,sbi_lead_entries.office_city,sbi_lead_entries.office_pin,sbi_lead_entries.office_phone,sbi_lead_entries.aadhaar_linked_mobile,sbi_lead_entries.appointment_date,sbi_lead_entries.appointment_time,sbi_lead_entries.card_applied,sbi_lead_entries.appointment_adrs, statuses.status as STATUS'))
            ->join('statuses', 'statuses.id', '=', 'sbi_lead_entries.status')
            ->where('pan_check', 1)->where('tl_status', "!=", null)->get();
        }
        
        $i = 0;
        if ($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
    public function showSbiData(Request $r)
    {
        $user = User::where('user_id', $r->id)->first();
        if ($user->role == 1) {
            $alltc = SbiLeadEntry::select(DB::raw('sbi_lead_entries.id as ID, sbi_lead_entries.name as NAME, sbi_lead_entries.pan as PAN,
        sbi_lead_entries.tc_id as TC, sbi_lead_entries.tl_id as TL, sbi_lead_entries.bm_id as BM, sbi_lead_entries.application_no as APPLICATION_NO,sbi_lead_entries.lead_ref as LEAD_REFERENCE, 
        sbi_lead_entries.tl_status as TL_STATUS,sbi_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, sbi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'sbi_lead_entries.status')
                ->where('bm_id', $r->id)->where('pan_check', 1)->get();
        } elseif ($user->role == 2) {
            $alltc = SbiLeadEntry::select(DB::raw('sbi_lead_entries.id as ID, sbi_lead_entries.name as NAME, sbi_lead_entries.pan as PAN,
        sbi_lead_entries.tc_id as TC, sbi_lead_entries.tl_id as TL, sbi_lead_entries.bm_id as BM, sbi_lead_entries.application_no as APPLICATION_NO, sbi_lead_entries.lead_ref as LEAD_REFERENCE, 
        sbi_lead_entries.tl_status as TL_STATUS,sbi_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, sbi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'sbi_lead_entries.status')
                ->where('tl_id', $r->id)->where('pan_check', 1)->get();
        } elseif ($user->role == 3) {
            $alltc = SbiLeadEntry::select(DB::raw('sbi_lead_entries.id as ID, sbi_lead_entries.name as NAME, sbi_lead_entries.pan as PAN,
        sbi_lead_entries.tc_id as TC, sbi_lead_entries.tl_id as TL, sbi_lead_entries.bm_id as BM, sbi_lead_entries.application_no as APPLICATION_NO,sbi_lead_entries.lead_ref as LEAD_REFERENCE, 
        sbi_lead_entries.tl_status as TL_STATUS,sbi_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, sbi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'sbi_lead_entries.status')
                ->where('tc_id', $r->id)->where('pan_check', 1)->get();
        } elseif ($user->role == 4) {
            $alltc = SbiLeadEntry::select(DB::raw('sbi_lead_entries.id as ID, sbi_lead_entries.name as NAME, sbi_lead_entries.pan as PAN,
        sbi_lead_entries.tc_id as TC, sbi_lead_entries.tl_id as TL, sbi_lead_entries.bm_id as BM, sbi_lead_entries.application_no as APPLICATION_NO, sbi_lead_entries.lead_ref as LEAD_REFERENCE, 
        sbi_lead_entries.tl_status as TL_STATUS,sbi_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, sbi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'sbi_lead_entries.status')
                ->where('pan_check', 1)->get();
        }

        $i = 0;
        if ($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
    public function getLeadSbi($lead_id)
    {
        $alltc = SbiLeadEntry::where('id', $lead_id)->first();


        return response()->json(['lead' => $alltc]);
    }

    public static function save_file(Request $request)
    {

        try {

            $file = $request->file;
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('/files');
            $file->move($destinationPath, $file_name);
            if ($request->type == 1) {
                SbiLeadEntry::where('id', $request->id)->update(['bank_document' => $file_name]);
                return response()->json(['msg' => "Bank Statement uploaded"]);
            } elseif ($request->type == 2) {
                SbiLeadEntry::where('id', $request->id)->update(['salary_slip' => $file_name]);
                return response()->json(['msg' => "Salary Slip uploaded"]);
            } elseif ($request->type == 3) {
                SbiLeadEntry::where('id', $request->id)->update(['pan_card' => $file_name]);
                return response()->json(['msg' => "Pan Card uploaded"]);
            } elseif ($request->type == 4) {
                SbiLeadEntry::where('id', $request->id)->update(['aadhar_card' => $file_name]);
                return response()->json(['msg' => "Aadhaar Card uploaded"]);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }
}
