<?php

namespace App\Http\Controllers;

use App\Models\CitiLeadEntry;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CitiController extends Controller
{
    public function lead_entry_citi(Request $r)
    {

        $validator = Validator::make($r->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'card_type' => 'required',
            'dob' => 'required',
            'pan' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'resi_address' => 'required',
            'resi_city' => 'required',
            'resi_pin' => 'required|integer',
            'curr_adrs_proof' => 'required',
            // 'resi_phone' => 'required|integer',
            'mobile' => 'required|integer',
            'email' => 'required|email',
            'occupation' => 'required',
            'company' => 'required',
            'designation' => 'required',
            'sarrogate' => 'required',

        ]);
         
            if ($validator->fails()) {
                //  Session::flash('msg', $validator->messages()->first());
                return response()->json(['msg' => $validator->messages()->first(), 'flag' => 0]);
            }
           
            try {
                if (empty($r->lead_id)) {
                    $tc = null;
                    $tl = null;
                    $bm = null;
                    $st =null;
                    $qry = Team::where("tc", $r->id)->first();
                    if (empty($qry)) {
                        $qry = Team::where("tl", $r->id)->get();
                        if (count($qry) == 0) {
                            $qry = Team::where("bm", $r->id)->get();
                            if (empty($qry)) {
                                return response()->json(['msg' => "Something Went Wrong"]);
                            } else {
                                $bm = $qry[0]->bm;
                                $st = 1;
                            }
                        } else {
                            $tl = $qry[0]->tl;
                            $bm = $qry[0]->bm;
                            $st = 1;
                        }
                    } else {
                        $tc = $qry->tc;
                        $tl = $qry->tl;
                        $bm = $qry->bm;
                        $st = 11;
                    }
                    CitiLeadEntry::create([

                        'fname' => $r->fname,
                        'lname' => $r->lname,
                        'card_type' => $r->card_type,
                        'dob' => $r->dob,
                        'pan' => $r->pan,
                        'father_name' => $r->father_name,
                        'mother_name' => $r->mother_name,
                        'resi_address' => $r->resi_address,
                        'resi_city' => $r->resi_city,
                        'resi_pin' => $r->resi_pin,
                        'curr_adrs_proof' => $r->curr_adrs_proof,
                        'resi_phone' => $r->resi_phone,
                        'mobile' => $r->mobile,
                        'email' => $r->email,
                        'occupation' => $r->occupation,
                        'company' => $r->company,
                        'designation' => $r->designation,
                        'sarrogate' => $r->sarrogate,
                        'education' => $r->education,
                        'marital_status' => $r->marital_status,
                        'sbi_ac' => $r->sbi_ac,
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
                        'lead_ref' => $r->lead_ref,
                        'bank_remark' => $r->bank_remark,
                        'card_limit' => $r->card_limit,
                        'tl_status'=>$r->tlstatus,
                        'status' => $st,
                        'tc_id' => $tc,
                        'tl_id' => $tl,
                        'bm_id' => $bm,
                    ]);
                    return response()->json(['msg' => "Lead Entry Submitted:)", 'flag' => 1]);
                } else {
                    CitiLeadEntry::where('id', $r->lead_id)->update([

                        'fname' => $r->fname,
                        'lname' => $r->lname,
                        'card_type' => $r->card_type,
                        'dob' => $r->dob,
                        'pan' => $r->pan,
                        'father_name' => $r->father_name,
                        'mother_name' => $r->mother_name,
                        'resi_address' => $r->resi_address,
                        'resi_city' => $r->resi_city,
                        'resi_pin' => $r->resi_pin,
                        'curr_adrs_proof' => $r->curr_adrs_proof,
                        'resi_phone' => $r->resi_phone,
                        'mobile' => $r->mobile,
                        'email' => $r->email,
                        'occupation' => $r->occupation,
                        'company' => $r->company,
                        'designation' => $r->designation,
                        'sarrogate' => $r->sarrogate,
                        'education' => $r->education,
                        'marital_status' => $r->marital_status,
                        'sbi_ac' => $r->sbi_ac,
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
                        'tl_status'=>$r->tlstatus,
                        'application_no' => $r->application_no,
                        'lead_ref' => $r->lead_ref,
                        'bank_remark' => $r->bank_remark,
                        'card_limit' => $r->card_limit,

                    ]);
                   
                        if ($r->role == 2 && $r->tlstatus == 'Approve') {
                            CitiLeadEntry::where('id', $r->lead_id)->update(['status' => 1,]);
                        }
                       
                    return response()->json(['msg' => "Updated Succesfully:)", 'flag' => 1]);
                }
            } catch (Exception $e) {
                return response()->json(['msg' => $e->getMessage(), 'flag' => 0]);
            }
        
    }


    public function getLeadCiti($lead_id)
    {
        $alltc = CitiLeadEntry::where('id', $lead_id)->first();


        return response()->json(['lead' => $alltc]);
    }

    // public function showCitiDuplicate(Request $r)
    // {

    //     $date = date_create($r->s_date);
    //     $s_date = date_format($date, "Y-m-d 00:00:00");
    //     $date = date_create($r->e_date);
    //     $e_date = date_format($date, "Y-m-d 23:59:59");
    //     $user = User::where('user_id', $r->id)->first();
    //     if ($user->role == 1) {

    //         $alltc = CitiLeadEntry::select(DB::raw('id as ID,created_at as Date, fname as FIRST_NAME,lname as LAST_NAME, pan as PAN,tc_id as TC, tl_id as TL, bm_id as BM '))
    //             ->where('bm_id', $r->id)->whereBetween('created_at', [$s_date, $e_date])->orderBy('id', 'DESC')->get();
    //         // return response()->json($alltc);
    //     } elseif ($user->role == 2) {

    //         $alltc = CitiLeadEntry::select(DB::raw('id as ID,created_at as Date, fname as FIRST_NAME,lname as LAST_NAME, pan as PAN,tc_id as TC, tl_id as TL, bm_id as BM '))
    //             ->where('tl_id', $r->id)->whereBetween('created_at', [$s_date, $e_date])->orderBy('id', 'DESC')->get();
    //         // return response()->json([$s_date,$e_date]);
    //     } elseif ($user->role == 3) {
    //         $alltc = CitiLeadEntry::select(DB::raw('id as ID,created_at as Date, fname as FIRST_NAME,lname as LAST_NAME, pan as PAN,tc_id as TC, tl_id as TL, bm_id as BM '))
    //             ->where('tc_id', $r->id)->whereBetween('created_at', [$s_date, $e_date])->orderBy('id', 'DESC')->get();
    //     } elseif ($user->role == 4) {
    //         $alltc = CitiLeadEntry::select(DB::raw('id as ID,created_at as Date, fname as FIRST_NAME,lname as LAST_NAME, pan as PAN,tc_id as TC, tl_id as TL, bm_id as BM '))
    //             ->whereBetween('created_at', [$s_date, $e_date])->orderBy('id', 'DESC')->get();
    //     }
    //     $i = 0;
    //     foreach ($alltc as $row) {
    //         if ($row->STATUS == 1) {
    //             $alltc[$i]->STATUS = 'OK';
    //         } else if ($row->STATUS == 0) {
    //             $alltc[$i]->STATUS = 'Pending';
    //         } else if ($row->STATUS == 2) {
    //             $alltc[$i]->STATUS = 'NOK';
    //         }
    //         $tc = User::where('user_id', $row->TC)->first();
    //         $tl = User::where('user_id', $row->TL)->first();
    //         $tm = User::where('user_id', $row->BM)->first();
    //         if (!empty($tc)) {
    //             $alltc[$i]->TC = $tc->name . '-' . $alltc[$i]->TC;
    //         }
    //         if (!empty($tl)) {
    //             $alltc[$i]->TL = $tl->name . '-' . $alltc[$i]->TL;
    //         }
    //         if (!empty($tm)) {
    //             $alltc[$i]->BM = $tm->name . '-' . $alltc[$i]->BM;
    //         }
    //         $i++;
    //     }
    //     if ($alltc) {
    //         return response()->json($alltc);
    //     }

    //     return response()->json(['message' => 'Not found!'], 404);
    // }
    public function showCitiData(Request $r)
    {
        // return response()->json($r->s_date);
        $date = date_create($r->s_date);
        $s_date = date_format($date, "Y-m-d 00:00:00");
        $date = date_create($r->e_date);
        $e_date = date_format($date, "Y-m-d 23:59:59");
        $user = User::where('user_id', $r->id)->first();
        if ($user->role == 1) {
            $alltc = CitiLeadEntry::select(DB::raw('citi_lead_entries.id as ID, citi_lead_entries.fname as FIRST_NAME,citi_lead_entries.lname as LAST_NAME, citi_lead_entries.pan as PAN,
        citi_lead_entries.tc_id as TC, citi_lead_entries.tl_id as TL, citi_lead_entries.bm_id as BM, citi_lead_entries.application_no as APPLICATION_NO,citi_lead_entries.lead_ref as LEAD_REFERENCE, 
        citi_lead_entries.tl_status as TL_STATUS,citi_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, citi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'citi_lead_entries.status')->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])
                ->where('bm_id', $r->id)->orderBy('citi_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 2) {
            $alltc = CitiLeadEntry::select(DB::raw('citi_lead_entries.id as ID, citi_lead_entries.fname as FIRST_NAME,citi_lead_entries.lname as LAST_NAME, citi_lead_entries.pan as PAN,
        citi_lead_entries.tc_id as TC, citi_lead_entries.tl_id as TL, citi_lead_entries.bm_id as BM, citi_lead_entries.application_no as APPLICATION_NO, citi_lead_entries.lead_ref as LEAD_REFERENCE, 
        citi_lead_entries.tl_status as TL_STATUS,citi_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, citi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'citi_lead_entries.status')->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])
                ->where('tl_id', $r->id)->orderBy('citi_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 3) {
            $alltc = CitiLeadEntry::select(DB::raw('citi_lead_entries.id as ID, citi_lead_entries.fname as FIRST_NAME,citi_lead_entries.lname as LAST_NAME, citi_lead_entries.pan as PAN,
        citi_lead_entries.tc_id as TC, citi_lead_entries.tl_id as TL, citi_lead_entries.bm_id as BM, citi_lead_entries.application_no as APPLICATION_NO,citi_lead_entries.lead_ref as LEAD_REFERENCE, 
        citi_lead_entries.tl_status as TL_STATUS,citi_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, citi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'citi_lead_entries.status')->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])
                ->where('tc_id', $r->id)->orderBy('citi_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 4) {
            $alltc = CitiLeadEntry::select(DB::raw('citi_lead_entries.id as ID, citi_lead_entries.fname as FIRST_NAME,citi_lead_entries.lname as LAST_NAME, citi_lead_entries.pan as PAN,
        citi_lead_entries.tc_id as TC, citi_lead_entries.tl_id as TL, citi_lead_entries.bm_id as BM, citi_lead_entries.application_no as APPLICATION_NO, citi_lead_entries.lead_ref as LEAD_REFERENCE, 
        citi_lead_entries.tl_status as TL_STATUS,citi_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, citi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'citi_lead_entries.status')->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])
                ->where('citi_lead_entries.status','!=', 11)->orderBy('citi_lead_entries.id', 'DESC')->get();
        }

        $i = 0;
        foreach ($alltc as $row) {
            $tc = User::where('user_id', $row->TC)->first();
            $tl = User::where('user_id', $row->TL)->first();
            $tm = User::where('user_id', $row->BM)->first();
            if (!empty($tc)) {
                $alltc[$i]->TC = $tc->name . '-' . $alltc[$i]->TC;
            }
            if (!empty($tl)) {
                $alltc[$i]->TL = $tl->name . '-' . $alltc[$i]->TL;
            }
            if (!empty($tm)) {
                $alltc[$i]->BM = $tm->name . '-' . $alltc[$i]->BM;
            }
            $i++;
        }
        if ($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
    public function showCitiSummaryTc(Request $r)
    {
        $date = date_create($r->s_date);
        $s_date = date_format($date, "Y-m-d 00:00:00");
        $date = date_create($r->e_date);
        $e_date = date_format($date, "Y-m-d 23:59:59");
        $usr = User::where('user_id', $r->id)->first();
        if ($usr->role == 1) {
            $alltc = User::select(DB::raw('user_id AS TC,teams.tl as TL,teams.bm as BM'))
                ->join('teams', 'users.user_id', '=', 'teams.tc')->join('roles', 'users.role', '=', 'roles.id')
                ->where('users.role', 3)->where('users.delete', 0)->where('teams.bm', $r->id)->get();
        } elseif ($usr->role == 2) {
            $alltc = User::select(DB::raw('user_id AS TC,teams.tl as TL,teams.bm as BM'))
                ->join('teams', 'users.user_id', '=', 'teams.tc')->join('roles', 'users.role', '=', 'roles.id')
                ->where('users.role', 3)->where('users.delete', 0)->where('teams.tl', $r->id)->get();
        } elseif ($usr->role == 4) {
            $alltc = User::select(DB::raw('user_id AS TC,teams.tl as TL,teams.bm as BM'))
                ->join('teams', 'users.user_id', '=', 'teams.tc')->join('roles', 'users.role', '=', 'roles.id')
                ->where('users.role', 3)->where('users.delete', 0)->get();
        }
        $i = 0;
        foreach ($alltc as $row) {
            $qd = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 1)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $acp = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 2)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $acr = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 3)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $nc = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 4)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $dec = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 5)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $apr = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 6)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $pfv = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 7)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $cb = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 8)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            // $acnr=CitiLeadEntry::where('tc_id',$row->TC)->where('status',9)->get();
            $ekyc = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 10)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $alltc[$i]->Verification_pending = count($pfv) + count($qd) + count($acp) + count($acr) + count($nc) + count($dec) + count($apr) + count($ekyc) + count($cb);
            $alltc[$i]->QD = count($qd) + count($acp) + count($acr) + count($nc) + count($dec) + count($apr) + count($cb);
            $alltc[$i]->App_code_pending = count($acp) + count($acr) + count($nc) + count($dec) + count($apr) + count($cb);
            $alltc[$i]->App_code_received = count($acr) + count($nc) + count($dec) + count($apr) + count($cb);
            $alltc[$i]->Need_correction = count($nc) + count($dec) + count($apr) + count($cb);
            $alltc[$i]->Decline = count($dec) + count($apr) + count($cb);
            $alltc[$i]->Approve = count($apr) + count($cb) + count($ekyc);
            $alltc[$i]->e_KYC_Done = count($ekyc) + count($cb);
            $alltc[$i]->Card_booked = count($cb);
            $tc = User::where('user_id', $row->TC)->first();
            $tl = User::where('user_id', $row->TL)->first();
            $tm = User::where('user_id', $row->BM)->first();
            if (!empty($tc)) {
                $alltc[$i]->TC = $tc->name . '-' . $alltc[$i]->TC;
            }
            if (!empty($tl)) {
                $alltc[$i]->TL = $tl->name . '-' . $alltc[$i]->TL;
            }
            if (!empty($tm)) {
                $alltc[$i]->BM = $tm->name . '-' . $alltc[$i]->BM;
            }
            $i++;
        }
        // dd($alltc);
        if ($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
    public function showCitiSummaryTl(Request $r)
    {
        $date = date_create($r->s_date);
        $s_date = date_format($date, "Y-m-d 00:00:00");
        $date = date_create($r->e_date);
        $e_date = date_format($date, "Y-m-d 23:59:59");
        $usr = User::where('user_id', $r->id)->first();
        if ($usr->role == 1) {
            $alltc = User::select(DB::raw('user_id AS TL,teams.bm as BM'))
                ->join('teams', 'users.user_id', '=', 'teams.tl')->join('roles', 'users.role', '=', 'roles.id')
                ->where('users.role', 2)->where('users.delete', 0)->where('teams.bm', $r->id)->where('teams.tc', 'TC')->get();
        } elseif ($usr->role == 2) {
            $alltc = User::select(DB::raw('user_id AS TL,teams.bm as BM'))
                ->join('teams', 'users.user_id', '=', 'teams.tl')->join('roles', 'users.role', '=', 'roles.id')
                ->where('users.role', 2)->where('users.delete', 0)->where('teams.tl', $r->id)->where('teams.tc', 'TC')->get();
        } elseif ($usr->role == 4) {
            $alltc = User::select(DB::raw('user_id AS TL,teams.bm as BM'))
                ->join('teams', 'users.user_id', '=', 'teams.tl')->join('roles', 'users.role', '=', 'roles.id')
                ->where('users.role', 2)->where('users.delete', 0)->where('teams.tc', 'TC')->get();
        }
        $i = 0;
        foreach ($alltc as $row) {
            $qd = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 1)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $acp = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 2)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $acr = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 3)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $nc = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 4)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $dec = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 5)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $apr = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 6)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $pfv = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 7)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $cb = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 8)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            // $acnr=CitiLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',9)->get();
            $ekyc = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 10)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $alltc[$i]->Verification_pending = count($pfv) + count($qd) + count($acp) + count($acr) + count($nc) + count($dec) + count($apr) + count($ekyc) + count($cb);
            $alltc[$i]->QD = count($qd) + count($acp) + count($acr) + count($nc) + count($dec) + count($apr) + count($cb);
            $alltc[$i]->App_code_pending = count($acp) + count($acr) + count($nc) + count($dec) + count($apr) + count($cb);
            $alltc[$i]->App_code_received = count($acr) + count($nc) + count($dec) + count($apr) + count($cb);
            $alltc[$i]->Need_correction = count($nc) + count($dec) + count($apr) + count($cb);
            $alltc[$i]->Decline = count($dec) + count($apr) + count($cb);
            $alltc[$i]->Approve = count($apr) + count($cb) + count($ekyc);
            $alltc[$i]->e_KYC_Done = count($ekyc) + count($cb);
            $alltc[$i]->Card_booked = count($cb);
            $tc = User::where('user_id', $row->TC)->first();
            $tl = User::where('user_id', $row->TL)->first();
            $tm = User::where('user_id', $row->BM)->first();
            if (!empty($tc)) {
                $alltc[$i]->TC = $tc->name . '-' . $alltc[$i]->TC;
            }
            if (!empty($tl)) {
                $alltc[$i]->TL = $tl->name . '-' . $alltc[$i]->TL;
            }
            if (!empty($tm)) {
                $alltc[$i]->BM = $tm->name . '-' . $alltc[$i]->BM;
            }
            $i++;
        }
        // dd($alltc);
        if ($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
    public function showCitiSummaryBm(Request $r)
    {
        $date = date_create($r->s_date);
        $s_date = date_format($date, "Y-m-d 00:00:00");
        $date = date_create($r->e_date);
        $e_date = date_format($date, "Y-m-d 23:59:59");
        $usr = User::where('user_id', $r->id)->first();
        if ($usr->role == 1) {
            $alltc = User::select(DB::raw('user_id AS BM'))
                ->join('roles', 'users.role', '=', 'roles.id')
                ->where('users.role', 1)->where('users.delete', 0)->get();
        } elseif ($usr->role == 2) {
            $alltc = User::select(DB::raw('user_id AS BM'))
                ->join('roles', 'users.role', '=', 'roles.id')
                ->where('users.role', 1)->where('users.delete', 0)->get();
        } elseif ($usr->role == 4) {
            $alltc = User::select(DB::raw('user_id AS BM'))
                ->join('roles', 'users.role', '=', 'roles.id')
                ->where('users.role', 1)->where('users.delete', 0)->get();
        }
        $i = 0;
        foreach ($alltc as $row) {
            $qd = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 1)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $acp = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 2)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $acr = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 3)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $nc = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 4)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $dec = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 5)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $apr = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 6)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $pfv = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 7)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $cb = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 8)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            // $acnr=CitiLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',9)->get();
            $ekyc = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 10)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $alltc[$i]->Verification_pending = count($pfv) + count($qd) + count($acp) + count($acr) + count($nc) + count($dec) + count($apr) + count($ekyc) + count($cb);
            $alltc[$i]->QD = count($qd) + count($acp) + count($acr) + count($nc) + count($dec) + count($apr) + count($cb);
            $alltc[$i]->App_code_pending = count($acp) + count($acr) + count($nc) + count($dec) + count($apr) + count($cb);
            $alltc[$i]->App_code_received = count($acr) + count($nc) + count($dec) + count($apr) + count($cb);
            $alltc[$i]->Need_correction = count($nc) + count($dec) + count($apr) + count($cb);
            $alltc[$i]->Decline = count($dec) + count($apr) + count($cb);
            $alltc[$i]->Approve = count($apr) + count($cb) + count($ekyc);
            $alltc[$i]->e_KYC_Done = count($ekyc) + count($cb);
            $alltc[$i]->Card_booked = count($cb);
            $tc = User::where('user_id', $row->TC)->first();
            $tl = User::where('user_id', $row->TL)->first();
            $tm = User::where('user_id', $row->BM)->first();
            if (!empty($tc)) {
                $alltc[$i]->TC = $tc->name . '-' . $alltc[$i]->TC;
            }
            if (!empty($tl)) {
                $alltc[$i]->TL = $tl->name . '-' . $alltc[$i]->TL;
            }
            if (!empty($tm)) {
                $alltc[$i]->BM = $tm->name . '-' . $alltc[$i]->BM;
            }
            $i++;
        }
        // dd($alltc);
        if ($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }

    public static function save_file_citi(Request $request)
    {

        try {

            $file = $request->file;
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('/files');
            $file->move($destinationPath, $file_name);
            if ($request->type == 1) {
                CitiLeadEntry::where('id', $request->id)->update(['bank_document' => $file_name]);
                return response()->json(['msg' => "Bank Statement uploaded"]);
            } elseif ($request->type == 2) {
                CitiLeadEntry::where('id', $request->id)->update(['salary_slip' => $file_name]);
                return response()->json(['msg' => "Salary Slip uploaded"]);
            } elseif ($request->type == 3) {
                CitiLeadEntry::where('id', $request->id)->update(['pan_card' => $file_name]);
                return response()->json(['msg' => "Pan Card uploaded"]);
            } elseif ($request->type == 4) {
                CitiLeadEntry::where('id', $request->id)->update(['aadhar_card' => $file_name]);
                return response()->json(['msg' => "Aadhaar Card uploaded"]);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }
}
