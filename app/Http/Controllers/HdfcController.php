<?php

namespace App\Http\Controllers;

use App\Models\HdfcLeadEntry;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HdfcController extends Controller
{
    public function lead_entry_hdfc(Request $r)
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
                                $st = 28;
                            }
                        } else {
                            $tl = $qry[0]->tl;
                            $bm = $qry[0]->bm;
                            $st = 28;
                        }
                    } else {
                        $tc = $qry->tc;
                        $tl = $qry->tl;
                        $bm = $qry->bm;
                        $st = 11;
                    }
                    HdfcLeadEntry::create([

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
                        'office_address' => $r->office_address,
                        'office_city' => $r->office_city,
                        'office_pin' => $r->office_pin,
                        'office_phone' => $r->office_phone,
                        'aadhaar_linked_mobile' => $r->aadhaar_linked_mobile,
                        'status' => $r->status,
                        'comment' => $r->comment,
                        'application_no' => $r->application_no,
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
                    HdfcLeadEntry::where('id', $r->lead_id)->update([

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
                        'office_address' => $r->office_address,
                        'office_city' => $r->office_city,
                        'office_pin' => $r->office_pin,
                        'office_phone' => $r->office_phone,
                        'aadhaar_linked_mobile' => $r->aadhaar_linked_mobile,
                        'status' => $r->status,
                        'comment' => $r->comment,
                        'tl_status'=>$r->tlstatus,
                        'application_no' => $r->application_no,
                        'bank_remark' => $r->bank_remark,
                        'card_limit' => $r->card_limit,

                    ]);
                   
                        if ($r->role == 2) {
                            if($r->tlstatus == 'Approve'){
                                HdfcLeadEntry::where('id', $r->lead_id)->update(['status' => 28,]);
                            }elseif($r->tlstatus == 'Reject'){
                                HdfcLeadEntry::where('id', $r->lead_id)->update(['status' => 5,]);
                            }elseif($r->tlstatus == 'e-KYC Done'){
                                HdfcLeadEntry::where('id', $r->lead_id)->update(['status' => 10,]);
                            }elseif($r->tlstatus == 'v-KYC Done'){
                                HdfcLeadEntry::where('id', $r->lead_id)->update(['status' => 15,]);
                            }elseif($r->tlstatus == 'Doc. Sent'){
                                HdfcLeadEntry::where('id', $r->lead_id)->update(['status' => 17,]);
                            }
                        }
                       
                    return response()->json(['msg' => "Updated Succesfully:)", 'flag' => 1]);
                }
            } catch (Exception $e) {
                return response()->json(['msg' => $e->getMessage(), 'flag' => 0]);
            }
        
    }


    public function getLeadHdfc($lead_id)
    {
        $alltc = HdfcLeadEntry::where('id', $lead_id)->first();


        return response()->json(['lead' => $alltc]);
    }

    
    public function showHdfcData(Request $r)
    {
        // return response()->json($r->s_date);
        $date = date_create($r->s_date);
        $s_date = date_format($date, "Y-m-d 00:00:00");
        $date = date_create($r->e_date);
        $e_date = date_format($date, "Y-m-d 23:59:59");
        $user = User::where('user_id', $r->id)->first();
        if ($user->role == 1) {
            $alltc = HdfcLeadEntry::select(DB::raw('hdfc_lead_entries.id as ID,hdfc_lead_entries.created_at as Date, hdfc_lead_entries.fname as FIRST_NAME,hdfc_lead_entries.lname as LAST_NAME, hdfc_lead_entries.pan as PAN,
        hdfc_lead_entries.tc_id as TC, hdfc_lead_entries.tl_id as TL, hdfc_lead_entries.bm_id as BM, hdfc_lead_entries.application_no as APPLICATION_NO, 
        hdfc_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, hdfc_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'hdfc_lead_entries.status')->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])
                ->where('bm_id', $r->id)->orderBy('hdfc_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 2) {
            $alltc = HdfcLeadEntry::select(DB::raw('hdfc_lead_entries.id as ID,hdfc_lead_entries.created_at as Date, hdfc_lead_entries.fname as FIRST_NAME,hdfc_lead_entries.lname as LAST_NAME, hdfc_lead_entries.pan as PAN,
        hdfc_lead_entries.tc_id as TC, hdfc_lead_entries.tl_id as TL, hdfc_lead_entries.bm_id as BM, hdfc_lead_entries.application_no as APPLICATION_NO,  
        hdfc_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, hdfc_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'hdfc_lead_entries.status')->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])
                ->where('tl_id', $r->id)->orderBy('hdfc_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 3) {
            $alltc = HdfcLeadEntry::select(DB::raw('hdfc_lead_entries.id as ID,hdfc_lead_entries.created_at as Date, hdfc_lead_entries.fname as FIRST_NAME,hdfc_lead_entries.lname as LAST_NAME, hdfc_lead_entries.pan as PAN,
        hdfc_lead_entries.tc_id as TC, hdfc_lead_entries.tl_id as TL, hdfc_lead_entries.bm_id as BM, hdfc_lead_entries.application_no as APPLICATION_NO, 
        hdfc_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, hdfc_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'hdfc_lead_entries.status')->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])
                ->where('tc_id', $r->id)->orderBy('hdfc_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 4) {
            $alltc = HdfcLeadEntry::select(DB::raw('hdfc_lead_entries.id as ID,hdfc_lead_entries.created_at as Date, hdfc_lead_entries.fname as FIRST_NAME,hdfc_lead_entries.lname as LAST_NAME, hdfc_lead_entries.pan as PAN,
        hdfc_lead_entries.tc_id as TC, hdfc_lead_entries.tl_id as TL, hdfc_lead_entries.bm_id as BM, hdfc_lead_entries.application_no as APPLICATION_NO,  
        hdfc_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, hdfc_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'hdfc_lead_entries.status')->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])
                ->where('hdfc_lead_entries.status','!=', 11)->orderBy('hdfc_lead_entries.id', 'DESC')->get();
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
    public function showHdfcSummaryTc(Request $r)
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
            $qd = HdfcLeadEntry::where('tc_id', $row->TC)->where('status', 1)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $acp = HdfcLeadEntry::where('tc_id', $row->TC)->where('status', 2)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $acr = HdfcLeadEntry::where('tc_id', $row->TC)->where('status', 3)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $nc = HdfcLeadEntry::where('tc_id', $row->TC)->where('status', 4)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $dec = HdfcLeadEntry::where('tc_id', $row->TC)->where('status', 5)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $apr = HdfcLeadEntry::where('tc_id', $row->TC)->where('status', 6)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $pfv = HdfcLeadEntry::where('tc_id', $row->TC)->where('status', 7)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $cb = HdfcLeadEntry::where('tc_id', $row->TC)->where('status', 8)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            // $acnr=HdfcLeadEntry::where('tc_id',$row->TC)->where('status',9)->get();
            $ekyc = HdfcLeadEntry::where('tc_id', $row->TC)->where('status', 10)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
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
    public function showHdfcSummaryTl(Request $r)
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
            $qd = HdfcLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 1)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $acp = HdfcLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 2)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $acr = HdfcLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 3)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $nc = HdfcLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 4)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $dec = HdfcLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 5)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $apr = HdfcLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 6)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $pfv = HdfcLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 7)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $cb = HdfcLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 8)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            // $acnr=HdfcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',9)->get();
            $ekyc = HdfcLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 10)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
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
    public function showHdfcSummaryBm(Request $r)
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
            $qd = HdfcLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 1)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $acp = HdfcLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 2)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $acr = HdfcLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 3)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $nc = HdfcLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 4)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $dec = HdfcLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 5)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $apr = HdfcLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 6)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $pfv = HdfcLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 7)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            $cb = HdfcLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 8)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
            // $acnr=HdfcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',9)->get();
            $ekyc = HdfcLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 10)->whereBetween('hdfc_lead_entries.created_at', [$s_date, $e_date])->get();
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

    public static function save_file_hdfc(Request $request)
    {

        try {

            $file = $request->file;
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('/files');
            $file->move($destinationPath, $file_name);
            if ($request->type == 1) {
                HdfcLeadEntry::where('id', $request->id)->update(['bank_document' => $file_name,'bank_pass'=>$request->bank_pass]);
                return response()->json(['msg' => "Bank Statement uploaded"]);
            } elseif ($request->type == 2) {
                HdfcLeadEntry::where('id', $request->id)->update(['salary_slip' => $file_name,'salary_pass'=>$request->salary_pass]);
                return response()->json(['msg' => "Salary Slip uploaded"]);
            } elseif ($request->type == 3) {
                HdfcLeadEntry::where('id', $request->id)->update(['pan_card' => $file_name,'pan_pass'=>$request->pan_pass]);
                return response()->json(['msg' => "Pan Card uploaded"]);
            } elseif ($request->type == 4) {
                HdfcLeadEntry::where('id', $request->id)->update(['aadhar_card' => $file_name,'aadhar_pass'=>$request->aadhar_pass]);
                return response()->json(['msg' => "Aadhaar Card uploaded"]);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    public static function delete_hdfc_lead(Request $request){

        try{
            HdfcLeadEntry::where('id',$request->id)->delete();
            return response()->json(['msg'=>"Lead Deleted"]);
        }catch(Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
 
    }
}
