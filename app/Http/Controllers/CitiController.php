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
    public function lead_entry_citi(Request $request)
    {
        // return response()->json(count($request->salary_slip));

        
        $card_limit = '';
        if (isset($request->card_limit) && $request->card_limit != 'undefined') {
            $card_limit = $request->card_limit;
        }
        $office_city = '';
        if (isset($request->office_city) && $request->office_city != 'undefined') {
            $office_city = $request->office_city;
        }
        $r = json_decode($request->data);
        if(empty($r->fname)){
            return response()->json(['msg'=>'fname is required', 'flag'=>0]);
        }
        if(empty($r->lname)){
            return response()->json(['msg'=>'lname is required', 'flag'=>0]);
        }
        if(empty($r->card_type)){
            return response()->json(['msg'=>'card_type is required', 'flag'=>0]);
        }
        if(empty($r->dob)){
            return response()->json(['msg'=>'dob is required', 'flag'=>0]);
        }
        if(empty($r->pan)){
            return response()->json(['msg'=>'pan is required', 'flag'=>0]);
        }
        if(empty($r->father_name)){
            return response()->json(['msg'=>'father_name is required', 'flag'=>0]);
        }
        if(empty($r->mother_name)){
            return response()->json(['msg'=>'mother_name is required', 'flag'=>0]);
        }
        if(empty($r->resi_address)){
            return response()->json(['msg'=>'resi_address is required', 'flag'=>0]);
        }
        if(empty($r->resi_city)){
            return response()->json(['msg'=>'resi_city is required', 'flag'=>0]);
        }
        if(empty($r->resi_pin)){
            return response()->json(['msg'=>'resi_pin is required', 'flag'=>0]);
        }
        if(empty($r->curr_adrs_proof)){
            return response()->json(['msg'=>'curr_adrs_proof is required', 'flag'=>0]);
        }
        if(empty($r->mobile)){
            return response()->json(['msg'=>'mobile is required', 'flag'=>0]);
        }
        if($r->resi_phone){
            $res= (!preg_match("/^[6-9][0-9]{9}$/", $r->resi_phone)) ? FALSE : TRUE;
            if($res == false){
                return response()->json(['msg'=>"Please Enter Valid resi phone", 'flag'=>0]);
            }
        }
        if($r->mobile){
            $res= (!preg_match("/^[6-9][0-9]{9}$/", $r->mobile)) ? FALSE : TRUE;
            if($res == false){
                return response()->json(['msg'=>"Please Enter Valid mobile", 'flag'=>0]);
            }
        }
        if(empty($r->email)){
            return response()->json(['msg'=>'email is required', 'flag'=>0]);
        }
        if($r->email){
            $res= (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $r->email)) ? FALSE : TRUE;
            if($res == false){
                return response()->json(['msg'=>"Please Enter Valid email", 'flag'=>0]);
            }
        }
        if(empty($r->occupation)){
            return response()->json(['msg'=>'occupation is required', 'flag'=>0]);
        }
        if(empty($r->company)){
            return response()->json(['msg'=>'company is required', 'flag'=>0]);
        }
        if(empty($r->designation)){
            return response()->json(['msg'=>'designation is required', 'flag'=>0]);
        }
        if(empty($r->sarrogate)){
            return response()->json(['msg'=>'sarrogate is required', 'flag'=>0]);
        }
        

        try {
            if (empty($r->lead_id)) {
                $tc = null;
                $tl = null;
                $bm = null;
                $st = null;
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
                $lead = CitiLeadEntry::create([

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
                    'office_city' => $office_city,
                    'office_pin' => $r->office_pin,
                    'office_phone' => $r->office_phone,
                    'aadhaar_linked_mobile' => $r->aadhaar_linked_mobile,
                    'status' => $r->status,
                    'comment' => $r->comment,
                    'application_no' => $r->application_no,
                    'bank_remark' => $r->bank_remark,
                    'tl_status' => $r->tlstatus,
                    'status' => $st,
                    'tc_id' => $tc,
                    'tl_id' => $tl,
                    'bm_id' => $bm,
                ]);
                if ($request->bank_doc != "null") {
                    $file = $request->bank_doc;
                    $file_name = time() . 'ba.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('/files');
                    $file->move($destinationPath, $file_name);
                    CitiLeadEntry::where('id', $lead->id)->update(['bank_document' => $file_name]);

                }
                if ($request->salary_slip != null) {
                    $allFile=array();
                    $i=0;
                    foreach($request->salary_slip as $row){
                        $file = $row;
                        $file_name = time().$i. 'sa.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('/files');
                        $file->move($destinationPath, $file_name);
                        array_push($allFile,$file_name);
                        $i++;
                    }
                    $allFile = json_encode($allFile);
                    $ad = CitiLeadEntry::where('id', $lead->id)->update(['salary_slip' => $allFile]);

                }
                if ($request->pan_card != "null") {
                    
                    $file = $request->pan_card;
                    $file_name = time() . 'pa.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('/files');
                    $file->move($destinationPath, $file_name);
                    CitiLeadEntry::where('id', $lead->id)->update(['pan_card' => $file_name]);
                    
                }
                if ($request->aadhar_card != "null") {
                    $file = $request->aadhar_card;
                    $file_name = time() . 'ad.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('/files');
                    $file->move($destinationPath, $file_name);
                    CitiLeadEntry::where('id', $lead->id)->update(['aadhar_card' => $file_name]);
                    
                }
                if ($request->other_doc != null) {
                    $allFile=array();
                    $i=0;
                    foreach($request->other_doc as $row){
                        $file = $row;
                        $file_name = time().$i. 'ot.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('/files');
                        $file->move($destinationPath, $file_name);
                        array_push($allFile,$file_name);
                        $i++;
                    }
                    $allFile = json_encode($allFile);
                    $ad = CitiLeadEntry::where('id', $lead->id)->update(['other_doc' => $allFile]);

                }
                
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
                    'office_address' => $r->office_address,
                    'office_city' => $r->office_city,
                    'office_pin' => $r->office_pin,
                    'office_phone' => $r->office_phone,
                    'aadhaar_linked_mobile' => $r->aadhaar_linked_mobile,
                    'status' => $r->status,
                    'comment' => $r->comment,
                    'tl_status' => $r->tlstatus,
                    'application_no' => $r->application_no,
                    'bank_remark' => $r->bank_remark,
                    'card_limit' => $card_limit,

                ]);

                if ($r->role == 2) {
                    if ($r->tlstatus == 'Approve') {
                        CitiLeadEntry::where('id', $r->lead_id)->update(['status' => 28,]);
                    } elseif ($r->tlstatus == 'Reject') {
                        CitiLeadEntry::where('id', $r->lead_id)->update(['status' => 5,]);
                    } elseif ($r->tlstatus == 'e-KYC Done') {
                        CitiLeadEntry::where('id', $r->lead_id)->update(['status' => 10,]);
                    } elseif ($r->tlstatus == 'v-KYC Done') {
                        CitiLeadEntry::where('id', $r->lead_id)->update(['status' => 15,]);
                    } elseif ($r->tlstatus == 'Doc. Sent') {
                        CitiLeadEntry::where('id', $r->lead_id)->update(['status' => 17,]);
                    }
                }
                if ($request->bank_doc != "null") {
                    $file = $request->bank_doc;
                    $file_name = time() . 'ba.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('/files');
                    $file->move($destinationPath, $file_name);
                    CitiLeadEntry::where('id', $r->lead_id)->update(['bank_document' => $file_name,'bank_pass'=>$request->bank_pass]);

                }
                if ($request->salary_slip != null) {
                    $allFile=array();
                    $i=0;
                    foreach($request->salary_slip as $row){
                        $file = $row;
                        $file_name = time().$i. 'sa.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('/files');
                        $file->move($destinationPath, $file_name);
                        array_push($allFile,$file_name);
                        $i++;
                    }
                    $allFile = json_encode($allFile);
                    $ad = CitiLeadEntry::where('id', $r->lead_id)->update(['salary_slip' => $allFile]);

                }
                if ($request->pan_card != "null") {
                    
                    $file = $request->pan_card;
                    $file_name = time() . 'pa.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('/files');
                    $file->move($destinationPath, $file_name);
                    CitiLeadEntry::where('id', $r->lead_id)->update(['pan_card' => $file_name]);
                    
                }
                if ($request->aadhar_card != "null") {
                    $file = $request->aadhar_card;
                    $file_name = time() . 'ad.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('/files');
                    $file->move($destinationPath, $file_name);
                    CitiLeadEntry::where('id', $r->lead_id)->update(['aadhar_card' => $file_name]);
                    
                }
                // return response()->json($request->other_doc);
                if ($request->other_doc != null) {

                    $allFile=array();
                    $i=0;
                    foreach($request->other_doc as $row){
                        $file = $row;
                        $file_name = time().$i. 'ot.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('/files');
                        $file->move($destinationPath, $file_name);
                        array_push($allFile,$file_name);
                        $i++;
                    }
                    $allFile = json_encode($allFile);
                    $ad = CitiLeadEntry::where('id', $r->lead_id)->update(['other_doc' => $allFile]);

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


    public function showCitiData(Request $r)
    {
        // return response()->json($r->s_date);
        $date = date_create($r->s_date);
        $s_date = date_format($date, "Y-m-d 00:00:00");
        $date = date_create($r->e_date);
        $e_date = date_format($date, "Y-m-d 23:59:59");
        $user = User::where('user_id', $r->id)->first();
        if ($user->role == 1) {
            $alltc = CitiLeadEntry::select(DB::raw('citi_lead_entries.id as ID,citi_lead_entries.created_at as Date, citi_lead_entries.fname as FIRST_NAME,citi_lead_entries.lname as LAST_NAME, citi_lead_entries.pan as PAN,
        citi_lead_entries.tc_id as TC, citi_lead_entries.tl_id as TL, citi_lead_entries.bm_id as BM, citi_lead_entries.application_no as APPLICATION_NO, 
        citi_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, citi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'citi_lead_entries.status')->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])
                ->where('bm_id', $r->id)->orderBy('citi_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 2) {
            $alltc = CitiLeadEntry::select(DB::raw('citi_lead_entries.id as ID,citi_lead_entries.created_at as Date, citi_lead_entries.fname as FIRST_NAME,citi_lead_entries.lname as LAST_NAME, citi_lead_entries.pan as PAN,
        citi_lead_entries.tc_id as TC, citi_lead_entries.tl_id as TL, citi_lead_entries.bm_id as BM, citi_lead_entries.application_no as APPLICATION_NO,  
        citi_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, citi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'citi_lead_entries.status')->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])
                ->where('tl_id', $r->id)->orderBy('citi_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 3) {
            $alltc = CitiLeadEntry::select(DB::raw('citi_lead_entries.id as ID,citi_lead_entries.created_at as Date, citi_lead_entries.fname as FIRST_NAME,citi_lead_entries.lname as LAST_NAME, citi_lead_entries.pan as PAN,
        citi_lead_entries.tc_id as TC, citi_lead_entries.tl_id as TL, citi_lead_entries.bm_id as BM, citi_lead_entries.application_no as APPLICATION_NO, 
        citi_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, citi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'citi_lead_entries.status')->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])
                ->where('tc_id', $r->id)->orderBy('citi_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 4) {
            $alltc = CitiLeadEntry::select(DB::raw('citi_lead_entries.id as ID,citi_lead_entries.created_at as Date, citi_lead_entries.fname as FIRST_NAME,citi_lead_entries.lname as LAST_NAME, citi_lead_entries.pan as PAN,
        citi_lead_entries.tc_id as TC, citi_lead_entries.tl_id as TL, citi_lead_entries.bm_id as BM, citi_lead_entries.application_no as APPLICATION_NO,  
        citi_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, citi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'citi_lead_entries.status')->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])
                ->where('citi_lead_entries.status', '!=', 11)->orderBy('citi_lead_entries.id', 'DESC')->get();
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
            $vp = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 11)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $dcd = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 18)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $nc = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 4)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $apr = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 6)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $dci = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 5)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $ekycp = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 13)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $ekycd = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 10)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $vkycp = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 14)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $vkycd = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 15)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $cb = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 8)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $cr = CitiLeadEntry::where('tc_id', $row->TC)->where('status', 12)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();

            $alltc[$i]->verification_pending = count($vp) + count($dcd) + count($nc) + count($apr) + count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->dip_call_done = count($dcd) + count($nc) + count($apr) + count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->Need_correction = count($nc) + count($apr) + count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->Approve = count($apr) + count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->Decline = count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->e_KYC_pending = count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->e_KYC_done = count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->v_KYC_pending = count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->v_KYC_done = count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->Card_booked = count($cb) + count($cr);
            $alltc[$i]->Card_reject = count($cr);
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
            $vp = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 11)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $dcd = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 18)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $nc = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 4)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $apr = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 6)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $dci = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 5)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $ekycp = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 13)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $ekycd = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 10)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $vkycp = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 14)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $vkycd = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 15)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $cb = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 8)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $cr = CitiLeadEntry::where('tl_id', $row->TL)->where('tc_id', null)->where('status', 12)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();

            $alltc[$i]->verification_pending = count($vp) + count($dcd) + count($nc) + count($apr) + count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->dip_call_done = count($dcd) + count($nc) + count($apr) + count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->Need_correction = count($nc) + count($apr) + count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->Approve = count($apr) + count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->Decline = count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->e_KYC_pending = count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->e_KYC_done = count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->v_KYC_pending = count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->v_KYC_done = count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->Card_booked = count($cb) + count($cr);
            $alltc[$i]->Card_reject = count($cr);
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
            $vp = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 11)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $dcd = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 18)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $nc = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 4)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $apr = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 6)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $dci = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 5)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $ekycp = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 13)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $ekycd = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 10)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $vkycp = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 14)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $vkycd = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 15)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $cb = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 8)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();
            $cr = CitiLeadEntry::where('bm_id', $row->BM)->where('tl_id', null)->where('tc_id', null)->where('status', 12)->whereBetween('citi_lead_entries.created_at', [$s_date, $e_date])->get();

            $alltc[$i]->verification_pending = count($vp) + count($dcd) + count($nc) + count($apr) + count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->dip_call_done = count($dcd) + count($nc) + count($apr) + count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->Need_correction = count($nc) + count($apr) + count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->Approve = count($apr) + count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->Decline = count($dci) + count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->e_KYC_pending = count($ekycp) + count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->e_KYC_done = count($ekycd) + count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->v_KYC_pending = count($vkycp) + count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->v_KYC_done = count($vkycd) + count($cb) + count($cr);
            $alltc[$i]->Card_booked = count($cb) + count($cr);
            $alltc[$i]->Card_reject = count($cr);
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
                CitiLeadEntry::where('id', $request->id)->update(['bank_document' => $file_name, 'bank_pass' => $request->bank_pass]);
                return response()->json(['msg' => "Bank Statement uploaded"]);
            } elseif ($request->type == 2) {
                CitiLeadEntry::where('id', $request->id)->update(['salary_slip' => $file_name, 'salary_pass' => $request->salary_pass]);
                return response()->json(['msg' => "Salary Slip uploaded"]);
            } elseif ($request->type == 3) {
                CitiLeadEntry::where('id', $request->id)->update(['pan_card' => $file_name, 'pan_pass' => $request->pan_pass]);
                return response()->json(['msg' => "Pan Card uploaded"]);
            } elseif ($request->type == 4) {
                CitiLeadEntry::where('id', $request->id)->update(['aadhar_card' => $file_name, 'aadhar_pass' => $request->aadhar_pass]);
                return response()->json(['msg' => "Aadhaar Card uploaded"]);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    public static function delete_citi_lead(Request $request)
    {
        try {
            CitiLeadEntry::where('id', $request->id)->delete();
            return response()->json(['msg' => "Lead Deleted"]);
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }
}
