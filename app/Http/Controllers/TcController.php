<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TcController extends Controller
{
    public function showTc(Request $r)
    {
        $usr = User::where('user_id',$r->id)->first();
        if($usr->role ==1){
            $alltc =User::select(DB::raw('user_id AS USER_ID, name AS NAME, email AS EMAIL, phone AS PHONE, roles.role as DESIGNATION'))
        ->join('teams','users.user_id', '=', 'teams.tc')->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',3)->where('users.delete',0)->where('teams.bm',$r->id)->orderBy('users.id', 'DESC')->get();
        }elseif($usr->role ==2){
            $alltc =User::select(DB::raw('user_id AS USER_ID, name AS NAME, email AS EMAIL, phone AS PHONE, roles.role as DESIGNATION'))
        ->join('teams','users.user_id', '=', 'teams.tc')->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',3)->where('users.delete',0)->where('teams.tl',$r->id)->orderBy('users.id', 'DESC')->get();
        }
        
        $i=0;
        foreach($alltc as $row){
            $yo=Team::select(DB::raw('users.name AS LEADER'))
            ->join('users','users.user_id', '=', 'teams.tl')->where('teams.tc',$row->USER_ID)->get();
            if(count($yo) > 0){
                $alltc[$i]->LEADER=$yo[0]->LEADER;
            }else{
                $alltc[$i]->LEADER='';
            }
            $i++;
        }
        // dd($alltc);
        if($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
    public function showTlToAdd()
    {
        $alltl = User::select(DB::raw('user_id AS USER_ID, name AS NAME'))
        ->where('role',2)->where('delete',0)->get();

        if($alltl) {
            return response()->json($alltl);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }

    public static function add_leader(Request $request){
        try{
            // dd($request);
            $data = Team::where('tc',$request->tc)->get();
            if(count($data) > 0){
                Team::where('tc',$request->tc)->update(['tl' => $request->tl,'bm' => $request->bm]);
                return response()->json(['msg'=>"Team Leader Updated"]);
            }else{
                Team::create(['tc' => $request->tc,'tl' => $request->tl,'bm' => $request->bm]);
                return response()->json(['msg'=>"Team Leader Added"]);
            }
        }catch(Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
 
    }
}
