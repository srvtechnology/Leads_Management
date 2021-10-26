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
        // return response()->json(['msg'=>$r->name]);

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
        if ($r->role == 2 ) {
            ScbLeadEntry::where('id', $r->lead_id)->update([
                'tl_status' => $r->tlstatus
            ]);
            if($r->tlstatus == 'Approve'){
                ScbLeadEntry::where('id', $r->lead_id)->update(['status' => 1,]);
            }
            return response()->json(['msg' => "Status Updated:)", 'flag' => 1]);
        }elseif ($r->role == 1 || $r->role == 4) {
            ScbLeadEntry::where('id', $r->lead_id)->update([
                'status' => $r->status,'lead_ref'=>$r->lead_ref,'bank_remark'=>$r->bank_remark,
                'comment' => $r->comment, 'application_no' => $r->application_no
            ]);
            if($r->tlstatus == 'Approve'){
                ScbLeadEntry::where('id', $r->lead_id)->update(['status' => 1,]);
            }
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
                if(empty($r->lead_id)){

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
                        'status' => 7,
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
                        
                    ]);
                    return response()->json(['msg' => "Updated Succesfully:)", 'flag' => 1]);

                }
            } catch (Exception $e) {
                return response()->json(['msg' => $e->getMessage(), 'flag' => 0]);
            }
        }
    }

    public function showScbSummary(Request $r)
    {
        $usr = User::where('user_id',$r->id)->first();
        if($usr->role ==1){
            $alltc =User::select(DB::raw('user_id AS TC,teams.tl as TL,teams.bm as BM'))
        ->join('teams','users.user_id', '=', 'teams.tc')->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',3)->where('users.delete',0)->where('teams.bm',$r->id)->get();
        }elseif($usr->role ==2){
            $alltc =User::select(DB::raw('user_id AS TC,teams.tl as TL,teams.bm as BM'))
        ->join('teams','users.user_id', '=', 'teams.tc')->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',3)->where('users.delete',0)->where('teams.tl',$r->id)->get();
        }
        $i=0;
        foreach($alltc as $row){
            $qd=ScbLeadEntry::where('tc_id',$row->TC)->where('status',1)->get();
            $acp=ScbLeadEntry::where('tc_id',$row->TC)->where('status',2)->get();
            $acr=ScbLeadEntry::where('tc_id',$row->TC)->where('status',3)->get();
            $nc=ScbLeadEntry::where('tc_id',$row->TC)->where('status',4)->get();
            $dec=ScbLeadEntry::where('tc_id',$row->TC)->where('status',5)->get();
            $apr=ScbLeadEntry::where('tc_id',$row->TC)->where('status',6)->get();
            $pfv=ScbLeadEntry::where('tc_id',$row->TC)->where('status',7)->get();
            $cb=ScbLeadEntry::where('tc_id',$row->TC)->where('status',8)->get();
            $alltc[$i]->QD= count($qd);
            $alltc[$i]->App_code_pending= count($acp);
            $alltc[$i]->App_code_received= count($acr);
            $alltc[$i]->Need_correction= count($nc);
            $alltc[$i]->Decline= count($dec);
            $alltc[$i]->Approve= count($apr);
            $alltc[$i]->Verification_pending= count($pfv);
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
    public function showScbData(Request $r)
    {
        $user = User::where('user_id', $r->id)->first();
        if ($user->role == 1) {
            $alltc = ScbLeadEntry::select(DB::raw('scb_lead_entries.id as ID, scb_lead_entries.salutation as Salutation, scb_lead_entries.fname as FIRST_NAME,scb_lead_entries.lname as LAST_NAME, scb_lead_entries.pan as PAN,
        scb_lead_entries.tc_id as TC, scb_lead_entries.tl_id as TL, scb_lead_entries.bm_id as BM, scb_lead_entries.application_no as APPLICATION_NO,scb_lead_entries.lead_ref as LEAD_REFERENCE, 
        scb_lead_entries.tl_status as TL_STATUS,scb_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, scb_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'scb_lead_entries.status')
                ->where('bm_id', $r->id)->where('scb_lead_entries.status','!=', 7)->orderBy('scb_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 2) {
            $alltc = ScbLeadEntry::select(DB::raw('scb_lead_entries.id as ID, scb_lead_entries.salutation as Salutation, scb_lead_entries.fname as FIRST_NAME,scb_lead_entries.lname as LAST_NAME, scb_lead_entries.pan as PAN,
        scb_lead_entries.tc_id as TC, scb_lead_entries.tl_id as TL, scb_lead_entries.bm_id as BM, scb_lead_entries.application_no as APPLICATION_NO, scb_lead_entries.lead_ref as LEAD_REFERENCE, 
        scb_lead_entries.tl_status as TL_STATUS,scb_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, scb_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'scb_lead_entries.status')
                ->where('tl_id', $r->id)->orderBy('scb_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 3) {
            $alltc = ScbLeadEntry::select(DB::raw('scb_lead_entries.id as ID, scb_lead_entries.salutation as Salutation, scb_lead_entries.fname as FIRST_NAME,scb_lead_entries.lname as LAST_NAME, scb_lead_entries.pan as PAN,
        scb_lead_entries.tc_id as TC, scb_lead_entries.tl_id as TL, scb_lead_entries.bm_id as BM, scb_lead_entries.application_no as APPLICATION_NO,scb_lead_entries.lead_ref as LEAD_REFERENCE, 
        scb_lead_entries.tl_status as TL_STATUS,scb_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, scb_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'scb_lead_entries.status')
                ->where('tc_id', $r->id)->orderBy('scb_lead_entries.id', 'DESC')->get();
        } elseif ($user->role == 4) {
            $alltc = ScbLeadEntry::select(DB::raw('scb_lead_entries.id as ID, scb_lead_entries.salutation as Salutation, scb_lead_entries.fname as FIRST_NAME,scb_lead_entries.lname as LAST_NAME, scb_lead_entries.pan as PAN,
        scb_lead_entries.tc_id as TC, scb_lead_entries.tl_id as TL, scb_lead_entries.bm_id as BM, scb_lead_entries.application_no as APPLICATION_NO, scb_lead_entries.lead_ref as LEAD_REFERENCE, 
        scb_lead_entries.tl_status as TL_STATUS,scb_lead_entries.bank_remark as BANK_REMARK, statuses.status as STATUS, scb_lead_entries.comment as REMARK'))
                ->join('statuses', 'statuses.id', '=', 'scb_lead_entries.status')
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
        }
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
            if ($request->type == 1) {
                ScbLeadEntry::where('id', $request->id)->update(['bank_document' => $file_name]);
                return response()->json(['msg' => "Bank Statement uploaded"]);
            } elseif ($request->type == 2) {
                ScbLeadEntry::where('id', $request->id)->update(['salary_slip' => $file_name]);
                return response()->json(['msg' => "Salary Slip uploaded"]);
            } elseif ($request->type == 3) {
                ScbLeadEntry::where('id', $request->id)->update(['pan_card' => $file_name]);
                return response()->json(['msg' => "Pan Card uploaded"]);
            } elseif ($request->type == 4) {
                ScbLeadEntry::where('id', $request->id)->update(['aadhar_card' => $file_name]);
                return response()->json(['msg' => "Aadhaar Card uploaded"]);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }
}
