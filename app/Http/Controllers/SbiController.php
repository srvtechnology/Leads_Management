<?php

namespace App\Http\Controllers;

use App\Models\SbiLeadEntry;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SbiController extends Controller
{
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
            if (count($qry) == 0) {
                $qry = Team::where("bm", $r->tc)->get();
                if (empty($qry)) {
                    return response()->json(['msg' => "Something Went Wrong"]);
                } else {
                    $data = SbiLeadEntry::create([
                        'pan' => $r->checkPan, 'fname' => $r->checkName,
                        'bm_id' => $qry[0]->bm
                    ]);
                    return response()->json(['msg' => "Plase wait for Pan Check!"]);
                }
            } else {
                $data = SbiLeadEntry::create([
                    'pan' => $r->checkPan, 'fname' => $r->checkName,
                    'tl_id' => $qry[0]->tl, 'bm_id' => $qry[0]->bm
                ]);
                return response()->json(['msg' => "Plase wait for Pan Check!"]);
            }
            return response()->json(['msg' => "tc mot found"]);
        } else {
            $data = SbiLeadEntry::create([
                'pan' => $r->checkPan, 'fname' => $r->checkName,
                'tc_id' => $qry->tc, 'tl_id' => $qry->tl, 'bm_id' => $qry->bm
            ]);
            return response()->json(['msg' => "Plase wait for Pan Check!"]);
        }
    }

    public function bm_check_pan(Request $r)
    {
        if ($r->code == 1) {
            $chk = SbiLeadEntry::where('id', $r->lead_id)->first();
            if($chk->tl_id == null){
                $data = SbiLeadEntry::where('id', $r->lead_id)->update(['pan_check' => 1,  'status' => 1]);
            }else{
                $data = SbiLeadEntry::where('id', $r->lead_id)->update(['pan_check' => 1,  'status' => 7]);
            }
            return response()->json(['msg' => "PAN verified!"]);
        } elseif ($r->code == 0) {
            $data = SbiLeadEntry::where('id', $r->lead_id)->update(['pan_check' => 2]);
            return response()->json(['msg' => "PAN Declined!"]);
        }
    }
   
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

    public function lead_entry_sbi(Request $request)
    {
        $r = json_decode($request->data);
        $card_limit = '';
        if (isset($request->card_limit) && $request->card_limit != 'undefined') {
            $card_limit = $request->card_limit;
        }
        if(empty($r->fname)){
            return response()->json(['msg'=>'fname is required', 'flag'=>0]);
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
        // $validator = Validator::make($r->all(), [

        //     'fname' => 'required',
        //     'dob' => 'required',
        //     'pan' => 'required',
        //     'father_name' => 'required',
        //     'mother_name' => 'required',
        //     'resi_address' => 'required',
        //     'resi_city' => 'required',
        //     'resi_pin' => 'required|integer',
        //     'curr_adrs_proof' => 'required',
        //     'mobile' => 'required|integer',
        //     'email' => 'required|email',
        //     'occupation' => 'required',
        //     'company' => 'required',
        //     'designation' => 'required',
        //     'sarrogate' => 'required',
        

        // ]);

        // if ($validator->fails()) {
        //     //  Session::flash('msg', $validator->messages()->first());
        //     return response()->json(['msg' => $validator->messages()->first(), 'flag' => 0]);
        // }
        
        try {
            SbiLeadEntry::where('id', $r->lead_id)->where('pan', $r->pan)->update([
                'salutation'=> $r->salutation,
                'fname' => $r->fname,
                'lname' => $r->lname,
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
                'lead_ref'=>$r->lead_ref,
                'bank_remark'=>$r->bank_remark,
                'card_limit'=>$card_limit,
                'tl_status' => $r->tlstatus,
            ]);
            if ($request->bank_doc != "null") {
                $file = $request->bank_doc;
                $file_name = time() . 'ba.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('/files');
                $file->move($destinationPath, $file_name);
                SbiLeadEntry::where('id', $r->lead_id)->update(['bank_document' => $file_name,'bank_pass'=>$request->bank_pass]);

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
                $ad = SbiLeadEntry::where('id', $r->lead_id)->update(['salary_slip' => $allFile]);

            }
            if ($request->pan_card != "null") {
                
                $file = $request->pan_card;
                $file_name = time() . 'pa.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('/files');
                $file->move($destinationPath, $file_name);
                SbiLeadEntry::where('id', $r->lead_id)->update(['pan_card' => $file_name]);
                
            }
            if ($request->aadhar_card != "null") {
                $file = $request->aadhar_card;
                $file_name = time() . 'ad.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('/files');
                $file->move($destinationPath, $file_name);
                SbiLeadEntry::where('id', $r->lead_id)->update(['aadhar_card' => $file_name]);
                
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
                $ad = SbiLeadEntry::where('id', $r->lead_id)->update(['other_doc' => $allFile]);

            }

            $urs = User::where('id',$r->id)->first();

            if ($urs->role == 2 && $r->tlstatus == 'Approve') {
                SbiLeadEntry::where('id', $r->lead_id)->where('pan', $r->pan)->update(['status' => 1]);
            }
            if ($urs->role == 2 && $r->tlstatus == 'e-KYC Done') {
                SbiLeadEntry::where('id', $r->lead_id)->where('pan', $r->pan)->update(['status' => 10]);
            }
            return response()->json(['msg' => "Updated Succesfully:)", 'flag' => 1]);
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage(), 'flag' => 0]);
        }
    }

    public function showSbiDuplicate(Request $r)
    {
        $date=date_create($r->s_date);
        $s_date= date_format($date,"Y-m-d 00:00:00");
        $date=date_create($r->e_date);
        $e_date= date_format($date,"Y-m-d 23:59:59");
        $user = User::where('user_id', $r->id)->first();
        if ($user->role == 1) {
            $alltc = SbiLeadEntry::select(DB::raw('id as ID,created_at as Date, fname as FIRST_NAME,lname as LAST_NAME, pan as PAN,tc_id as TC, tl_id as TL, bm_id as BM, pan_check as STATUS '))
                ->where('bm_id', $r->id)->whereBetween('created_at', [$s_date,$e_date])->orderBy('id', 'DESC')->get();
        } elseif ($user->role == 2) {
            
            $alltc = SbiLeadEntry::select(DB::raw('id as ID,created_at as Date, fname as FIRST_NAME,lname as LAST_NAME, pan as PAN,tc_id as TC, tl_id as TL, bm_id as BM, pan_check as STATUS '))
            ->where('tl_id', $r->id)->whereBetween('created_at', [$s_date,$e_date])->orderBy('id', 'DESC')->get();
            // return response()->json([$s_date,$e_date]);
        } elseif ($user->role == 3) {
            $alltc = SbiLeadEntry::select(DB::raw('id as ID,created_at as Date, fname as FIRST_NAME,lname as LAST_NAME, pan as PAN,tc_id as TC, tl_id as TL, bm_id as BM, pan_check as STATUS '))
                ->where('tc_id', $r->id)->whereBetween('created_at', [$s_date,$e_date])->orderBy('id', 'DESC')->get();
        } elseif ($user->role == 4) {
            $alltc = SbiLeadEntry::select(DB::raw('id as ID,created_at as Date, fname as FIRST_NAME,lname as LAST_NAME, pan as PAN,tc_id as TC, tl_id as TL, bm_id as BM, pan_check as STATUS '))
                ->whereBetween('created_at', [$s_date,$e_date])->orderBy('id', 'DESC')->get();
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
    public function showSbiData(Request $r)
    {
        // return response()->json($r->s_date,$r->e_date);
        $date=date_create($r->s_date);
        $s_date= date_format($date,"Y-m-d 00:00:00");
        $date=date_create($r->e_date);
        $e_date= date_format($date,"Y-m-d 23:59:59");
        $user = User::where('user_id', $r->id)->first();
        if ($user->role == 1) {
            $alltc = SbiLeadEntry::select(DB::raw('sbi_lead_entries.id as ID, sbi_lead_entries.created_at as Date, sbi_lead_entries.fname as FIRST_NAME,sbi_lead_entries.lname as LAST_NAME, sbi_lead_entries.pan as PAN,
        sbi_lead_entries.tc_id as TC, sbi_lead_entries.tl_id as TL, sbi_lead_entries.bm_id as BM, sbi_lead_entries.application_no as APPLICATION_NO,sbi_lead_entries.lead_ref as LEAD_REFERENCE, 
        sbi_lead_entries.tl_status as TL_STATUS,sbi_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, sbi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'sbi_lead_entries.status')->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])
                ->where('bm_id', $r->id)->where('pan_check', 1)->orderBy('sbi_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 2) {
            $alltc = SbiLeadEntry::select(DB::raw('sbi_lead_entries.id as ID, sbi_lead_entries.created_at as Date, sbi_lead_entries.fname as FIRST_NAME,sbi_lead_entries.lname as LAST_NAME, sbi_lead_entries.pan as PAN,
        sbi_lead_entries.tc_id as TC, sbi_lead_entries.tl_id as TL, sbi_lead_entries.bm_id as BM, sbi_lead_entries.application_no as APPLICATION_NO, sbi_lead_entries.lead_ref as LEAD_REFERENCE, 
        sbi_lead_entries.tl_status as TL_STATUS,sbi_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, sbi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'sbi_lead_entries.status')->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])
                ->where('tl_id', $r->id)->where('pan_check', 1)->orderBy('sbi_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 3) {
            $alltc = SbiLeadEntry::select(DB::raw('sbi_lead_entries.id as ID, sbi_lead_entries.created_at as Date, sbi_lead_entries.fname as FIRST_NAME,sbi_lead_entries.lname as LAST_NAME, sbi_lead_entries.pan as PAN,
        sbi_lead_entries.tc_id as TC, sbi_lead_entries.tl_id as TL, sbi_lead_entries.bm_id as BM, sbi_lead_entries.application_no as APPLICATION_NO,sbi_lead_entries.lead_ref as LEAD_REFERENCE, 
        sbi_lead_entries.tl_status as TL_STATUS,sbi_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, sbi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'sbi_lead_entries.status')->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])
                ->where('tc_id', $r->id)->where('pan_check', 1)->orderBy('sbi_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 4) {
            $alltc = SbiLeadEntry::select(DB::raw('sbi_lead_entries.id as ID, sbi_lead_entries.created_at as Date, sbi_lead_entries.fname as FIRST_NAME,sbi_lead_entries.lname as LAST_NAME, sbi_lead_entries.pan as PAN,
        sbi_lead_entries.tc_id as TC, sbi_lead_entries.tl_id as TL, sbi_lead_entries.bm_id as BM, sbi_lead_entries.application_no as APPLICATION_NO, sbi_lead_entries.lead_ref as LEAD_REFERENCE, 
        sbi_lead_entries.tl_status as TL_STATUS,sbi_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, sbi_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'sbi_lead_entries.status')->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])
                ->where('pan_check', 1)->where('sbi_lead_entries.status','!=', 7)->orderBy('sbi_lead_entries.id', 'DESC')->get();
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
    public function showSbiSummaryTc(Request $r)
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
            $qd=SbiLeadEntry::where('tc_id',$row->TC)->where('status',1)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $acp=SbiLeadEntry::where('tc_id',$row->TC)->where('status',2)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $acr=SbiLeadEntry::where('tc_id',$row->TC)->where('status',3)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $nc=SbiLeadEntry::where('tc_id',$row->TC)->where('status',4)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $dec=SbiLeadEntry::where('tc_id',$row->TC)->where('status',5)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $apr=SbiLeadEntry::where('tc_id',$row->TC)->where('status',6)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $pfv=SbiLeadEntry::where('tc_id',$row->TC)->where('status',7)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $cb=SbiLeadEntry::where('tc_id',$row->TC)->where('status',8)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            // $acnr=SbiLeadEntry::where('tc_id',$row->TC)->where('status',9)->get();
            $ekyc=SbiLeadEntry::where('tc_id',$row->TC)->where('status',10)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
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
    public function showSbiSummaryTl(Request $r)
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
            $qd=SbiLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',1)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $acp=SbiLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',2)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $acr=SbiLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',3)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $nc=SbiLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',4)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $dec=SbiLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',5)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $apr=SbiLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',6)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $pfv=SbiLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',7)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $cb=SbiLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',8)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            // $acnr=SbiLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',9)->get();
            $ekyc=SbiLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',10)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
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
    public function showSbiSummaryBm(Request $r)
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
            $qd=SbiLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',1)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $acp=SbiLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',2)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $acr=SbiLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',3)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $nc=SbiLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',4)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $dec=SbiLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',5)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $apr=SbiLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',6)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $pfv=SbiLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',7)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            $cb=SbiLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',8)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
            // $acnr=SbiLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',9)->get();
            $ekyc=SbiLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',10)->whereBetween('sbi_lead_entries.created_at', [$s_date,$e_date])->get();
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
    
    public function getLeadSbi($lead_id)
    {
        $alltc = SbiLeadEntry::where('id', $lead_id)->first();


        return response()->json(['lead' => $alltc]);
    }

    public static function save_file_sbi(Request $request)
    {
        try {

            $file = $request->file;
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('/files');
            $file->move($destinationPath, $file_name);
            if ($request->type == 1) {
                SbiLeadEntry::where('id', $request->id)->update(['bank_document' => $file_name,'bank_pass'=>$request->bank_pass]);
                return response()->json(['msg' => "Bank Statement uploaded"]);
            } elseif ($request->type == 2) {
                SbiLeadEntry::where('id', $request->id)->update(['salary_slip' => $file_name,'salary_pass'=>$request->salary_pass]);
                return response()->json(['msg' => "Salary Slip uploaded"]);
            } elseif ($request->type == 3) {
                SbiLeadEntry::where('id', $request->id)->update(['pan_card' => $file_name,'pan_pass'=>$request->pan_pass]);
                return response()->json(['msg' => "Pan Card uploaded"]);
            } elseif ($request->type == 4) {
                SbiLeadEntry::where('id', $request->id)->update(['aadhar_card' => $file_name,'aadhar_pass'=>$request->aadhar_pass]);
                return response()->json(['msg' => "Aadhaar Card uploaded"]);
            }elseif ($request->type == 5) {
                SbiLeadEntry::where('id', $request->id)->update(['other_doc' => $file_name,'other_doc_pass'=>$request->other_doc_pass]);
                return response()->json(['msg' => "Other Doc uploaded"]);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }
    public static function delete_sbi_lead(Request $request){
        try{
            SbiLeadEntry::where('id',$request->id)->delete();
            return response()->json(['msg'=>"User Deleted"]);
        }catch(Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
 
    }
}
