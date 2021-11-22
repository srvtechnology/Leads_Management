<?php

namespace App\Http\Controllers;

use App\Models\HsbcLeadEntry;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class HsbcController extends Controller
{
    public function lead_entry_hsbc(Request $request)
    {
        $r = json_decode($request->data);
        $card_limit = '';
        if (isset($request->card_limit) && $request->card_limit != 'undefined') {
            $card_limit = $request->card_limit;
        }
        if(empty($r->salutation)){
            return response()->json(['msg'=>'salutation is required', 'flag'=>0]);
        }
        if(empty($r->fname)){
            return response()->json(['msg'=>'fname is required', 'flag'=>0]);
        }
        if(empty($r->lname)){
            return response()->json(['msg'=>'lname is required', 'flag'=>0]);
        }
        if(empty($r->card_type)){
            return response()->json(['msg'=>'card_type is required', 'flag'=>0]);
        }
        if(empty($r->pan)){
            return response()->json(['msg'=>'pan is required', 'flag'=>0]);
        }
        if(empty($r->dob)){
            return response()->json(['msg'=>'dob is required', 'flag'=>0]);
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
        if(empty($r->birth_place)){
            return response()->json(['msg'=>'birth_place is required', 'flag'=>0]);
        }
        if(empty($r->aadhaar)){
            return response()->json(['msg'=>'aadhaar is required', 'flag'=>0]);
        }
        if(empty($r->aadhaar_linked_mobile)){
            return response()->json(['msg'=>'aadhaar_linked_mobile is required', 'flag'=>0]);
        }
        if(empty($r->mother_name)){
            return response()->json(['msg'=>'mother_name is required', 'flag'=>0]);
        }
        if(empty($r->father_name)){
            return response()->json(['msg'=>'father_name is required', 'flag'=>0]);
        }
        if(empty($r->dependent)){
            return response()->json(['msg'=>'dependent is required', 'flag'=>0]);
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
        if(empty($r->company)){
            return response()->json(['msg'=>'company is required', 'flag'=>0]);
        }
        if(empty($r->designation)){
            return response()->json(['msg'=>'designation is required', 'flag'=>0]);
        }
        if(empty($r->id)){
            return response()->json(['msg'=>'id is required', 'flag'=>0]);
        }
        // $validator = Validator::make($r->all(), [
        //     'email' => 'required|email',
        //     'salutation' => 'required',
        //     'fname' => 'required',
        //     'lname' => 'required',
        //     'card_type' => 'required',
        //     'mobile' => 'required',
        //     'pan' => 'required',
        //     'dob' => 'required',
        //     'birth_place' => 'required',
        //     'aadhaar' => 'required|integer',
        //     'aadhaar_linked_mobile' => 'required',
        //     'mother_name' => 'required',
        //     'father_name' => 'required',
        //     'dependent' => 'required',
        //     'resi_address' => 'required',
        //     'resi_city' => 'required',
        //     'resi_pin' => 'required|integer',
        //     'company' => 'required',
        //     'designation' => 'required',
        //     'id' => 'required',

        // ]);
        
        //     if ($validator->fails()) {
        //         //  Session::flash('msg', $validator->messages()->first());
        //         return response()->json(['msg' => $validator->messages()->first(), 'flag' => 0]);
        //     }
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
                                $st = 39;
                            }
                        } else {
                            $tl = $qry[0]->tl;
                            $bm = $qry[0]->bm;
                            $st = 39;
                        }
                    } else {
                        $tc = $qry->tc;
                        $tl = $qry->tl;
                        $bm = $qry->bm;
                        $st = 11;
                    }
                    $lead= HsbcLeadEntry::create([
    
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
                    if ($request->bank_doc != "null") {
                        $file = $request->bank_doc;
                        $file_name = time() . 'ba.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('/files');
                        $file->move($destinationPath, $file_name);
                        HsbcLeadEntry::where('id', $lead->id)->update(['bank_document' => $file_name]);
    
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
                        $ad = HsbcLeadEntry::where('id', $lead->id)->update(['salary_slip' => $allFile]);
    
                    }
                    if ($request->pan_card != "null") {
                        
                        $file = $request->pan_card;
                        $file_name = time() . 'pa.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('/files');
                        $file->move($destinationPath, $file_name);
                        HsbcLeadEntry::where('id', $lead->id)->update(['pan_card' => $file_name]);
                        
                    }
                    if ($request->aadhar_card != "null") {
                        $file = $request->aadhar_card;
                        $file_name = time() . 'ad.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('/files');
                        $file->move($destinationPath, $file_name);
                        HsbcLeadEntry::where('id', $lead->id)->update(['aadhar_card' => $file_name]);
                        
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
                        $ad = HsbcLeadEntry::where('id', $lead->id)->update(['other_doc' => $allFile]);
    
                    }
                    return response()->json(['msg' => "Lead Entry Submitted:)", 'flag' => 1]);
                }else{
                    HsbcLeadEntry::where('id',$r->lead_id)->update([
    
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
                        'card_limit' => $card_limit,
                        
                    ]);

                    if ($r->role == 2) {
                        if($r->tlstatus == 'Approve' && $r->status == 0){
                            HsbcLeadEntry::where('id', $r->lead_id)->update(['status' => 6,]);
                        }elseif($r->tlstatus == 'Reject'){
                            HsbcLeadEntry::where('id', $r->lead_id)->update(['status' => 5,]);
                        }elseif($r->tlstatus == 'v-KYC Done'){
                            HsbcLeadEntry::where('id', $r->lead_id)->update(['status' => 15,]);
                        }elseif($r->tlstatus == 'e-Sign Mail Done'){
                            HsbcLeadEntry::where('id', $r->lead_id)->update(['status' => 35,]);
                        }elseif($r->tlstatus == 'Call Done'){
                            HsbcLeadEntry::where('id', $r->lead_id)->update(['status' => 37,]);
                        }elseif($r->tlstatus == 'Doc. Uploaded'){
                            HsbcLeadEntry::where('id', $r->lead_id)->update(['status' => 17,]);
                        }
                    }
                    if ($request->bank_doc != "null") {
                        $file = $request->bank_doc;
                        $file_name = time() . 'ba.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('/files');
                        $file->move($destinationPath, $file_name);
                        HsbcLeadEntry::where('id', $r->lead_id)->update(['bank_document' => $file_name,'bank_pass'=>$request->bank_pass]);
    
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
                        $ad = HsbcLeadEntry::where('id', $r->lead_id)->update(['salary_slip' => $allFile]);
    
                    }
                    if ($request->pan_card != "null") {
                        
                        $file = $request->pan_card;
                        $file_name = time() . 'pa.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('/files');
                        $file->move($destinationPath, $file_name);
                        HsbcLeadEntry::where('id', $r->lead_id)->update(['pan_card' => $file_name]);
                        
                    }
                    if ($request->aadhar_card != "null") {
                        $file = $request->aadhar_card;
                        $file_name = time() . 'ad.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('/files');
                        $file->move($destinationPath, $file_name);
                        HsbcLeadEntry::where('id', $r->lead_id)->update(['aadhar_card' => $file_name]);
                        
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
                        $ad = HsbcLeadEntry::where('id', $r->lead_id)->update(['other_doc' => $allFile]);
    
                    }
    
                    return response()->json(['msg' => "Updated Succesfully:)", 'flag' => 1]);

                }
            } catch (Exception $e) {
                return response()->json(['msg' => $e->getMessage(), 'flag' => 0]);
            }
        
    }

    
    public function showHsbcData(Request $r)
    {
        // return response()->json($r->s_date);
        $date=date_create($r->s_date);
        $s_date= date_format($date,"Y-m-d 00:00:00");
        $date=date_create($r->e_date);
        $e_date= date_format($date,"Y-m-d 23:59:59");
        $user = User::where('user_id', $r->id)->first();
        // return response()->json(['message' => 'check']);
        if ($user->role == 1) {

            $alltc = HsbcLeadEntry::select(DB::raw('hsbc_lead_entries.id as ID, hsbc_lead_entries.created_at as Date, hsbc_lead_entries.fname as FIRST_NAME,hsbc_lead_entries.lname as LAST_NAME, hsbc_lead_entries.pan as PAN,
        hsbc_lead_entries.tc_id as TC, hsbc_lead_entries.tl_id as TL, hsbc_lead_entries.bm_id as BM, hsbc_lead_entries.application_no as APPLICATION_NO,
        hsbc_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, hsbc_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'hsbc_lead_entries.status')->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])
                ->where('bm_id', $r->id)->orderBy('hsbc_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 2) {
            $alltc = HsbcLeadEntry::select(DB::raw('hsbc_lead_entries.id as ID, hsbc_lead_entries.created_at as Date, hsbc_lead_entries.fname as FIRST_NAME,hsbc_lead_entries.lname as LAST_NAME, hsbc_lead_entries.pan as PAN,
        hsbc_lead_entries.tc_id as TC, hsbc_lead_entries.tl_id as TL, hsbc_lead_entries.bm_id as BM, hsbc_lead_entries.application_no as APPLICATION_NO, 
        hsbc_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, hsbc_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'hsbc_lead_entries.status')->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])
                ->where('tl_id', $r->id)->orderBy('hsbc_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 3) {
            $alltc = HsbcLeadEntry::select(DB::raw('hsbc_lead_entries.id as ID, hsbc_lead_entries.created_at as Date, hsbc_lead_entries.fname as FIRST_NAME,hsbc_lead_entries.lname as LAST_NAME, hsbc_lead_entries.pan as PAN,
        hsbc_lead_entries.tc_id as TC, hsbc_lead_entries.tl_id as TL, hsbc_lead_entries.bm_id as BM, hsbc_lead_entries.application_no as APPLICATION_NO,
        hsbc_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, hsbc_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'hsbc_lead_entries.status')->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])
                ->where('tc_id', $r->id)->orderBy('hsbc_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 4) {
            $alltc = HsbcLeadEntry::select(DB::raw('hsbc_lead_entries.id as ID, hsbc_lead_entries.created_at as Date, hsbc_lead_entries.fname as FIRST_NAME,hsbc_lead_entries.lname as LAST_NAME, hsbc_lead_entries.pan as PAN,
        hsbc_lead_entries.tc_id as TC, hsbc_lead_entries.tl_id as TL, hsbc_lead_entries.bm_id as BM, hsbc_lead_entries.application_no as APPLICATION_NO, 
        hsbc_lead_entries.tl_status as TL_STATUS, statuses.status as STATUS, hsbc_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'hsbc_lead_entries.status')->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])
                ->where('hsbc_lead_entries.status','!=', 11)->orderBy('hsbc_lead_entries.id', 'DESC')->get();
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

    public function showHsbcSummaryTc(Request $r)
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
            $nc=HsbcLeadEntry::where('tc_id',$row->TC)->where('status',4)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $esignp=HsbcLeadEntry::where('tc_id',$row->TC)->where('status',30)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $esignd=HsbcLeadEntry::where('tc_id',$row->TC)->where('status',26)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $vkycp=HsbcLeadEntry::where('tc_id',$row->TC)->where('status',14)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $vkycd=HsbcLeadEntry::where('tc_id',$row->TC)->where('status',15)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            
            $alltc[$i]->Need_correction= count($nc)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd);
            $alltc[$i]->e_sign_pending= count($esignp)+count($esignd)+count($vkycp)+count($vkycd);
            $alltc[$i]->e_sign_done= count($esignd)+count($vkycp)+count($vkycd);
            $alltc[$i]->v_KYC_pending= count($vkycp)+count($vkycd);
            $alltc[$i]->v_KYC_done= count($vkycd);
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
    public function showHsbcSummaryTl(Request $r)
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
            $lead=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',19)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $cpv=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',20)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $cpvr=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',21)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $nc=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',4)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $aip=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',22)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $aipd=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',24)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $aipa=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',23)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $aap=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',31)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $aad=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',27)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $esignp=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',30)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $esignd=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',26)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $vkycp=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',14)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $vkycd=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',15)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $cb=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',8)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $cr=HsbcLeadEntry::where('tl_id',$row->TL)->where('tc_id',null)->where('status',12)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            
            $alltc[$i]->lead= count($lead)+count($cpv)+count($cpvr)+count($nc)+count($aip)+count($aipd)+count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->CPV= count($cpv)+count($cpvr)+count($nc)+count($aip)+count($aipd)+count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->CPV_reject= count($cpvr)+count($nc)+count($aip)+count($aipd)+count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->Need_correction= count($nc)+count($aip)+count($aipd)+count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->api= count($aip)+count($aipd)+count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->api_decline= count($aipd)+count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->api_approve= count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->aadhar_auth_pending=count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->aadhar_auth_done =count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->e_sign_pending= count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->e_sign_done= count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->v_KYC_pending= count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->v_KYC_done= count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->Card_booked= count($cb)+count($cr);
            $alltc[$i]->Card_reject= count($cr);
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
    public function showHsbcSummaryBm(Request $r)
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
            $lead=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',19)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $cpv=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',20)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $cpvr=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',21)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $nc=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',4)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $aip=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',22)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $aipd=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',24)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $aipa=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',23)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $aap=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',31)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $aad=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',27)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $esignp=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',30)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $esignd=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',26)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $vkycp=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',14)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $vkycd=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',15)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $cb=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',8)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            $cr=HsbcLeadEntry::where('bm_id',$row->BM)->where('tl_id',null)->where('tc_id',null)->where('status',12)->whereBetween('hsbc_lead_entries.created_at', [$s_date,$e_date])->get();
            
            $alltc[$i]->lead= count($lead)+count($cpv)+count($cpvr)+count($nc)+count($aip)+count($aipd)+count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->CPV= count($cpv)+count($cpvr)+count($nc)+count($aip)+count($aipd)+count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->CPV_reject= count($cpvr)+count($nc)+count($aip)+count($aipd)+count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->Need_correction= count($nc)+count($aip)+count($aipd)+count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->api= count($aip)+count($aipd)+count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->api_decline= count($aipd)+count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->api_approve= count($aipa)+count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->aadhar_auth_pending=count($aap)+count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->aadhar_auth_done =count($aad)+count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->e_sign_pending= count($esignp)+count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->e_sign_done= count($esignd)+count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->v_KYC_pending= count($vkycp)+count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->v_KYC_done= count($vkycd)+count($cb)+count($cr);
            $alltc[$i]->Card_booked= count($cb)+count($cr);
            $alltc[$i]->Card_reject= count($cr);  
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
    public function getLeadHsbc($lead_id)
    {
        $alltc = HsbcLeadEntry::where('id', $lead_id)->first();


        return response()->json(['lead' => $alltc]);
    }

    public static function save_file_hsbc(Request $request)
    {

        try {

            $file = $request->file;
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('/files');
            $file->move($destinationPath, $file_name);
            if ($request->type == 1) {
                HsbcLeadEntry::where('id', $request->id)->update(['bank_document' => $file_name,'bank_pass'=>$request->bank_pass]);
                return response()->json(['msg' => "Bank Statement uploaded"]);
            } elseif ($request->type == 2) {
                HsbcLeadEntry::where('id', $request->id)->update(['salary_slip' => $file_name,'salary_pass'=>$request->salary_pass]);
                return response()->json(['msg' => "Salary Slip uploaded"]);
            } elseif ($request->type == 3) {
                HsbcLeadEntry::where('id', $request->id)->update(['pan_card' => $file_name,'pan_pass'=>$request->pan_pass]);
                return response()->json(['msg' => "Pan Card uploaded"]);
            } elseif ($request->type == 4) {
                HsbcLeadEntry::where('id', $request->id)->update(['aadhar_card' => $file_name,'aadhar_pass'=>$request->aadhar_pas]);
                return response()->json(['msg' => "Aadhaar Card uploaded"]);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    public static function delete_hsbc_lead(Request $request){
        try{
            HsbcLeadEntry::where('id',$request->id)->delete();
            return response()->json(['msg'=>"Lead Deleted"]);
        }catch(Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
 
    }
}
