<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;

class LeaveController extends Controller
{
    public static function applyForLeave(Request $r){
        $validator = Validator::make($r->all(),[
            'startDate'=> 'required',
            'endDate'=> 'required',
            'Subject'=> 'required',
            'Discp'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => $validator->messages()->first(), 'flag' => 0]);
        }
        $tc = null;
        $tl = null;
        $bm = null;
        $qry = Team::where("tc", $r->user_id)->first(); 
        if(empty($qry)) {
            $qry = Team::where("tl", $r->user_id)->get();
            if (count($qry) == 0) {
                return response()->json(['msg' => "Something Went Wrong"]);
            } else {
                $tl = $qry[0]->tl;
                $bm = $qry[0]->bm;
    
            }
        } else {
            $tc = $qry->tc;
            $tl = $qry->tl;
            $bm = $qry->bm;

        }
        try{
            Leave::create([
                'tc'=> $tc,
                'tl'=> $tl,
                'bm'=> $bm,
                'from_date'=> $r->startDate,
                'to_date'=> $r->endDate,
                'subject'=> $r->Subject,
                'reason'=> $r->Discp,
                'status'=>33
            ]);
            return response()->json(['msg' => "Application Submitted Sucessfully",'flag' => 1]);
        }catch(Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }

    public static function bmUpdateLeave(Request $r){
       
        try{
            Leave::where('id',$r->leave_id)->update([
                'status'=>$r->flag,
                'remark'=>$r->Remark
            ]);
            return response()->json(['msg' => "Application Updated Sucessfully",'flag' => 1]);
        }catch(Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }

    public static function getAllLeave(Request $r){
        if($r->role == 1){
            $data = Leave::select(DB::raw('leaves.id, leaves.tc, leaves.tl, leaves.bm, leaves.from_date, leaves.to_date,leaves.subject, statuses.status, leaves.remark'))
            ->join('statuses', 'statuses.id', '=', 'leaves.status')
            ->where('leaves.bm',$r->user_id)->get();
        }elseif($r->role == 2){
            $data = Leave::select(DB::raw('leaves.id, leaves.tl, leaves.bm, leaves.from_date, leaves.to_date,leaves.subject, statuses.status, leaves.remark'))
            ->join('statuses', 'statuses.id', '=', 'leaves.status')
            ->where('leaves.tc',null)->where('leaves.tl',$r->user_id)->get();
        }elseif($r->role == 3){
            $data = Leave::select(DB::raw('leaves.id, leaves.tc, leaves.tl, leaves.bm, leaves.from_date, leaves.to_date,leaves.subject, statuses.status, leaves.remark'))
            ->join('statuses', 'statuses.id', '=', 'leaves.status')
            ->where('leaves.tc',$r->user_id)->get();
        }
        return response()->json($data);
    }

    public static function getLeaveInfo(Request $r){
        $data = Leave::select(DB::raw('leaves.id, leaves.tc, leaves.tl, leaves.bm, leaves.from_date, leaves.to_date,leaves.subject, statuses.status, leaves.reason, leaves.remark'))
        ->join('statuses', 'statuses.id', '=', 'leaves.status')->where('leaves.id',$r->leave_id)->first();
        if($data->tc != null){
            $user = User::where('user_id',$data->tc)->first();
            $data->name = $user->name;
            $data->email = $user->email;
            $data->phone =$user->phone;
        }elseif($data->tc == null && $data->tl != null){
            $user = User::where('user_id',$data->tl)->first();
            $data->name = $user->name;
            $data->email = $user->email;
            $data->phone =$user->phone;
        }
        return response()->json($data);
    }

    public static function deleteLeave(Request $r){
        try{
            Leave::where('id',$r->leave_id)->delete();
            return response()->json(['msg'=>"Leave Deleted"]);
        }catch(Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
       
    }
}
