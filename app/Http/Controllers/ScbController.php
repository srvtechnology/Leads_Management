<?php

namespace App\Http\Controllers;

use App\Models\ScbLeadEntry;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ScbController extends Controller
{
    public function lead_entry_scb(Request $r)
    {
        // return response()->json(['msg'=>$r->tc]);

        $validator = Validator::make($r->all(), [
            'email' => 'required|email',
            'salutation' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'card_type' => 'required',
            'mobile' => 'required',
            'pan' => 'required',
            'dob' => 'required',
            'birth_place' => 'required',
            'aadhaar' => 'required|integer',
            'aadhaar_linked_mobile' => 'required',
            'mother_name' => 'required',
            'father_name' => 'required',
            'dependent' => 'required',
            'resi_address' => 'required',
            'resi_city' => 'required',
            'resi_pin' => 'required|integer',
            // 'resi_status' => 'required',
            // 'current_rest_time' => 'required',
            // 'marital_status' => 'required',
            // 'spouse_name' => 'required',
            'company' => 'required',
            'designation' => 'required',
            // 'current_company_experience' => 'required',
            // 'total_experience' => 'required',
            // 'office_email' => 'required',
            // 'pf' => 'required',
            // 'office_address' => 'required',
            // 'office_city' => 'required',
            // 'office_pin' => 'required',
            // 'office_landline' => 'required',
            // 'comm_address' => 'required',
            // 'nature_of_bussiness' => 'required',
            // 'industry' => 'required',
            'id' => 'required',

        ]);
        
            if ($validator->fails()) {
                //  Session::flash('msg', $validator->messages()->first());
                return response()->json(['msg' => $validator->messages()->first(), 'flag' => 0]);
            }
            try {
                if(empty($r->lead_id)){
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
                                $st = 20;
                            }
                        } else {
                            $tl = $qry[0]->tl;
                            $bm = $qry[0]->bm;
                            $st = 20;
                        }
                    } else {
                        $tc = $qry->tc;
                        $tl = $qry->tl;
                        $bm = $qry->bm;
                        $st = 19;
                    }
                    ScbLeadEntry::create([
    
                        'card_type' => $r->card_type,
                        'salutation' => $r->salutation,
                        'fname' => $r->fname,
                        'lname' => $r->lname,
                        'mobile' => $r->mobile,
                        'pan' => $r->pan,
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
                        'tc_id' => $tc,
                        'tl_id' => $tl,
                        'bm_id' => $bm,
                        'status' => $st,
                    ]);

                    return response()->json(['msg' => "Lead Entry Submitted:)", 'flag' => 1]);
                }else{
                    ScbLeadEntry::where('id',$r->lead_id)->update([
    
                        'card_type' => $r->card_type,
                        'salutation' => $r->salutation,
                        'fname' => $r->fname,
                        'lname' => $r->lname,
                        'mobile' => $r->mobile,
                        'pan' => $r->pan,
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
                        'status' => $r->status,
                        'comment' => $r->comment,
                        'tl_status'=>$r->tlstatus,
                        'application_no' => $r->application_no,
                        'card_limit' => $r->card_limit,
                        
                    ]);

                    if ($r->role == 2) {
                        if($r->tlstatus == 'Approve' && $r->status == 0){
                            ScbLeadEntry::where('id', $r->lead_id)->update(['status' => 20,]);
                        }elseif($r->tlstatus == 'Reject'){
                            ScbLeadEntry::where('id', $r->lead_id)->update(['status' => 5,]);
                        }elseif($r->tlstatus == 'v-KYC Done'){
                            ScbLeadEntry::where('id', $r->lead_id)->update(['status' => 15,]);
                        }elseif($r->tlstatus == 'e-Sign Done'){
                            ScbLeadEntry::where('id', $r->lead_id)->update(['status' => 26,]);
                        }elseif($r->tlstatus == 'Aadhaar Auth Done'){
                            ScbLeadEntry::where('id', $r->lead_id)->update(['status' => 27,]);
                        }
                    }
                    return response()->json(['msg' => "Updated Succesfully:)", 'flag' => 1]);

                }
            } catch (Exception $e) {
                return response()->json(['msg' => $e->getMessage(), 'flag' => 0]);
            }
        
    }

    
    public function showScbData(Request $r)
    {
        // return response()->json($r->s_date);
        $date=date_create($r->s_date);
        $s_date= date_format($date,"Y-m-d 00:00:00");
        $date=date_create($r->e_date);
        $e_date= date_format($date,"Y-m-d 23:59:59");
        $user = User::where('user_id', $r->id)->first();
        // return response()->json(['message' => 'check']);
        if ($user->role == 1) {

            $alltc = ScbLeadEntry::select(DB::raw('scb_lead_entries.id as ID, scb_lead_entries.created_at as Date, scb_lead_entries.fname as FIRST_NAME,scb_lead_entries.lname as LAST_NAME, scb_lead_entries.pan as PAN,
        scb_lead_entries.tc_id as TC, scb_lead_entries.tl_id as TL, scb_lead_entries.bm_id as BM, scb_lead_entries.application_no as AIP_NO,
        scb_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, scb_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'scb_lead_entries.status')->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])
                ->where('bm_id', $r->id)->orderBy('scb_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 2) {
            $alltc = ScbLeadEntry::select(DB::raw('scb_lead_entries.id as ID, scb_lead_entries.created_at as Date, scb_lead_entries.fname as FIRST_NAME,scb_lead_entries.lname as LAST_NAME, scb_lead_entries.pan as PAN,
        scb_lead_entries.tc_id as TC, scb_lead_entries.tl_id as TL, scb_lead_entries.bm_id as BM, scb_lead_entries.application_no as AIP_NO, 
        scb_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, scb_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'scb_lead_entries.status')->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])
                ->where('tl_id', $r->id)->orderBy('scb_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 3) {
            $alltc = ScbLeadEntry::select(DB::raw('scb_lead_entries.id as ID, scb_lead_entries.created_at as Date, scb_lead_entries.fname as FIRST_NAME,scb_lead_entries.lname as LAST_NAME, scb_lead_entries.pan as PAN,
        scb_lead_entries.tc_id as TC, scb_lead_entries.tl_id as TL, scb_lead_entries.bm_id as BM, scb_lead_entries.application_no as AIP_NO,
        scb_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, scb_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'scb_lead_entries.status')->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])
                ->where('tc_id', $r->id)->orderBy('scb_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 4) {
            $alltc = ScbLeadEntry::select(DB::raw('scb_lead_entries.id as ID, scb_lead_entries.created_at as Date, scb_lead_entries.fname as FIRST_NAME,scb_lead_entries.lname as LAST_NAME, scb_lead_entries.pan as PAN,
        scb_lead_entries.tc_id as TC, scb_lead_entries.tl_id as TL, scb_lead_entries.bm_id as BM, scb_lead_entries.application_no as AIP_NO, 
        scb_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, scb_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'scb_lead_entries.status')->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])
                ->where('scb_lead_entries.status','!=', 7)->orderBy('scb_lead_entries.id', 'DESC')->get();
        }

        $i = 0;
        foreach($alltc as $row){
            $tc=User::where('user_id',$row->TC)->first();
            $tl=User::where('user_id',$row->TL)->first();
            $tm=User::where('user_id',$row->BM)->first();
            if(!empty($tc)){
                $alltc[$i]->TC = $tc->name.'-'.$alltc[$i]->TC;
            }
            if(!empty($tl)){
                $alltc[$i]->TL = $tl->name.'-'.$alltc[$i]->TL;
            }
            if(!empty($tm)){
                $alltc[$i]->BM = $tm->name.'-'.$alltc[$i]->BM;
            }   
            $i++;
        }
        if ($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }

    public function showScbSummaryTc(Request $r)
    {
        $date=date_create($r->s_date);
        $s_date= date_format($date,"Y-m-d 00:00:00");
        $date=date_create($r->e_date);
        $e_date= date_format($date,"Y-m-d 23:59:59");
        $usr = User::where('user_id',$r->id)->first();
        if($usr->role ==1){
            $alltc =User::select(DB::raw('user_id AS TC,teams.tl as TL,teams.bm as BM'))
        ->join('teams','users.user_id', '=', 'teams.tc')->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',3)->where('users.delete',0)->where('teams.bm',$r->id)->get();
        }elseif($usr->role ==2){
            $alltc =User::select(DB::raw('user_id AS TC,teams.tl as TL,teams.bm as BM'))
        ->join('teams','users.user_id', '=', 'teams.tc')->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',3)->where('users.delete',0)->where('teams.tl',$r->id)->get();
        }elseif($usr->role ==4){
            $alltc =User::select(DB::raw('user_id AS TC,teams.tl as TL,teams.bm as BM'))
        ->join('teams','users.user_id', '=', 'teams.tc')->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',3)->where('users.delete',0)->get();
        }
        $i=0;
        foreach($alltc as $row){
            $qd=ScbLeadEntry::where('tc_id',$row->TC)->where('status',1)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $acp=ScbLeadEntry::where('tc_id',$row->TC)->where('status',2)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $acr=ScbLeadEntry::where('tc_id',$row->TC)->where('status',3)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $nc=ScbLeadEntry::where('tc_id',$row->TC)->where('status',4)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $dec=ScbLeadEntry::where('tc_id',$row->TC)->where('status',5)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $apr=ScbLeadEntry::where('tc_id',$row->TC)->where('status',6)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $pfv=ScbLeadEntry::where('tc_id',$row->TC)->where('status',7)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $cb=ScbLeadEntry::where('tc_id',$row->TC)->where('status',8)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            // $acnr=ScbLeadEntry::where('tc_id',$row->TC)->where('status',9)->get();
            $ekyc=ScbLeadEntry::where('tc_id',$row->TC)->where('status',10)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $alltc[$i]->Verification_pending= count($pfv)+ count($qd)+count($acp)+count($acr)+count($nc)+count($dec)+count($apr)+count($ekyc)+count($cb);
            $alltc[$i]->QD= count($qd)+count($acp)+count($acr)+count($nc)+count($dec)+count($apr)+count($cb);
            $alltc[$i]->App_code_pending= count($acp)+count($acr)+count($nc)+count($dec)+count($apr)+count($cb);
            $alltc[$i]->App_code_received= count($acr)+count($nc)+count($dec)+count($apr)+count($cb);
            $alltc[$i]->Need_correction= count($nc)+count($dec)+count($apr)+count($cb);
            $alltc[$i]->Decline= count($dec)+count($apr)+count($cb);
            $alltc[$i]->Approve= count($apr)+count($cb)+count($ekyc);
            $alltc[$i]->e_KYC_Done= count($ekyc)+count($cb); 
            $alltc[$i]->Card_booked= count($cb);  
            $tc=User::where('user_id',$row->TC)->first();
            $tl=User::where('user_id',$row->TL)->first();
            $tm=User::where('user_id',$row->BM)->first();
            if(!empty($tc)){
                $alltc[$i]->TC = $tc->name.'-'.$alltc[$i]->TC;
            }
            if(!empty($tl)){
                $alltc[$i]->TL = $tl->name.'-'.$alltc[$i]->TL;
            }
            if(!empty($tm)){
                $alltc[$i]->BM = $tm->name.'-'.$alltc[$i]->BM;
            }   
            $i++;
        }
        // dd($alltc);
        if($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
    public function showScbSummaryTl(Request $r)
    {
        $date=date_create($r->s_date);
        $s_date= date_format($date,"Y-m-d 00:00:00");
        $date=date_create($r->e_date);
        $e_date= date_format($date,"Y-m-d 23:59:59");
        $usr = User::where('user_id',$r->id)->first();
        if($usr->role ==1){
            $alltc =User::select(DB::raw('user_id AS TL,teams.bm as BM'))
        ->join('teams','users.user_id', '=', 'teams.tl')->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',2)->where('users.delete',0)->where('teams.bm',$r->id)->where('teams.tc','TC')->get();
        }elseif($usr->role ==2){
            $alltc =User::select(DB::raw('user_id AS TL,teams.bm as BM'))
        ->join('teams','users.user_id', '=', 'teams.tl')->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',2)->where('users.delete',0)->where('teams.tl',$r->id)->where('teams.tc','TC')->get();
        }elseif($usr->role ==4){
            $alltc =User::select(DB::raw('user_id AS TL,teams.bm as BM'))
        ->join('teams','users.user_id', '=', 'teams.tl')->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',2)->where('users.delete',0)->where('teams.tc','TC')->get();
        }
        $i=0;
        foreach($alltc as $row){
            $qd=ScbLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',1)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $acp=ScbLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',2)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $acr=ScbLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',3)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $nc=ScbLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',4)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $dec=ScbLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',5)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $apr=ScbLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',6)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $pfv=ScbLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',7)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $cb=ScbLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',8)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            // $acnr=ScbLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',9)->get();
            $ekyc=ScbLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',10)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $alltc[$i]->Verification_pending= count($pfv)+ count($qd)+count($acp)+count($acr)+count($nc)+count($dec)+count($apr)+count($ekyc)+count($cb);
            $alltc[$i]->QD= count($qd)+count($acp)+count($acr)+count($nc)+count($dec)+count($apr)+count($cb);
            $alltc[$i]->App_code_pending= count($acp)+count($acr)+count($nc)+count($dec)+count($apr)+count($cb);
            $alltc[$i]->App_code_received= count($acr)+count($nc)+count($dec)+count($apr)+count($cb);
            $alltc[$i]->Need_correction= count($nc)+count($dec)+count($apr)+count($cb);
            $alltc[$i]->Decline= count($dec)+count($apr)+count($cb);
            $alltc[$i]->Approve= count($apr)+count($cb)+count($ekyc);
            $alltc[$i]->e_KYC_Done= count($ekyc)+count($cb); 
            $alltc[$i]->Card_booked= count($cb);  
            $tc=User::where('user_id',$row->TC)->first();
            $tl=User::where('user_id',$row->TL)->first();
            $tm=User::where('user_id',$row->BM)->first();
            if(!empty($tc)){
                $alltc[$i]->TC = $tc->name.'-'.$alltc[$i]->TC;
            }
            if(!empty($tl)){
                $alltc[$i]->TL = $tl->name.'-'.$alltc[$i]->TL;
            }
            if(!empty($tm)){
                $alltc[$i]->BM = $tm->name.'-'.$alltc[$i]->BM;
            }   
            $i++;
        }
        // dd($alltc);
        if($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
    public function showScbSummaryBm(Request $r)
    {
        $date=date_create($r->s_date);
        $s_date= date_format($date,"Y-m-d 00:00:00");
        $date=date_create($r->e_date);
        $e_date= date_format($date,"Y-m-d 23:59:59");
        $usr = User::where('user_id',$r->id)->first();
        if($usr->role ==1){
            $alltc =User::select(DB::raw('user_id AS BM'))
        ->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',1)->where('users.delete',0)->get();
        }elseif($usr->role ==2){
            $alltc =User::select(DB::raw('user_id AS BM'))
        ->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',1)->where('users.delete',0)->get();
        }elseif($usr->role ==4){
            $alltc =User::select(DB::raw('user_id AS BM'))
        ->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',1)->where('users.delete',0)->get();
        }
        $i=0;
        foreach($alltc as $row){
            $qd=ScbLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',1)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $acp=ScbLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',2)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $acr=ScbLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',3)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $nc=ScbLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',4)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $dec=ScbLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',5)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $apr=ScbLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',6)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $pfv=ScbLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',7)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $cb=ScbLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',8)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            // $acnr=ScbLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',9)->get();
            $ekyc=ScbLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',10)->whereBetween('scb_lead_entries.created_at', [$s_date,$e_date])->get();
            $alltc[$i]->Verification_pending= count($pfv)+ count($qd)+count($acp)+count($acr)+count($nc)+count($dec)+count($apr)+count($ekyc)+count($cb);
            $alltc[$i]->QD= count($qd)+count($acp)+count($acr)+count($nc)+count($dec)+count($apr)+count($cb);
            $alltc[$i]->App_code_pending= count($acp)+count($acr)+count($nc)+count($dec)+count($apr)+count($cb);
            $alltc[$i]->App_code_received= count($acr)+count($nc)+count($dec)+count($apr)+count($cb);
            $alltc[$i]->Need_correction= count($nc)+count($dec)+count($apr)+count($cb);
            $alltc[$i]->Decline= count($dec)+count($apr)+count($cb);
            $alltc[$i]->Approve= count($apr)+count($cb)+count($ekyc);
            $alltc[$i]->e_KYC_Done= count($ekyc)+count($cb); 
            $alltc[$i]->Card_booked= count($cb);  
            $tc=User::where('user_id',$row->TC)->first();
            $tl=User::where('user_id',$row->TL)->first();
            $tm=User::where('user_id',$row->BM)->first();
            if(!empty($tc)){
                $alltc[$i]->TC = $tc->name.'-'.$alltc[$i]->TC;
            }
            if(!empty($tl)){
                $alltc[$i]->TL = $tl->name.'-'.$alltc[$i]->TL;
            }
            if(!empty($tm)){
                $alltc[$i]->BM = $tm->name.'-'.$alltc[$i]->BM;
            }   
            $i++;
        }
        // dd($alltc);
        if($alltc) {
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
            if ($request->type == 1) {
                ScbLeadEntry::where('id', $request->id)->update(['bank_document' => $file_name,'bank_pass'=>$request->bank_pass]);
                return response()->json(['msg' => "Bank Statement uploaded"]);
            } elseif ($request->type == 2) {
                ScbLeadEntry::where('id', $request->id)->update(['salary_slip' => $file_name,'salary_pass'=>$request->salary_pass]);
                return response()->json(['msg' => "Salary Slip uploaded"]);
            } elseif ($request->type == 3) {
                ScbLeadEntry::where('id', $request->id)->update(['pan_card' => $file_name,'pan_pass'=>$request->pan_pass]);
                return response()->json(['msg' => "Pan Card uploaded"]);
            } elseif ($request->type == 4) {
                ScbLeadEntry::where('id', $request->id)->update(['aadhar_card' => $file_name,'aadhar_pass'=>$request->aadhar_pas]);
                return response()->json(['msg' => "Aadhaar Card uploaded"]);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    public static function delete_scb_lead(Request $request){
        try{
            ScbLeadEntry::where('id',$request->id)->delete();
            return response()->json(['msg'=>"Lead Deleted"]);
        }catch(Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
 
    }
}
