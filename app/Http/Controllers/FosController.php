<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FosController extends Controller
{
    public function showFos(Request $r)
    {
        $usr = User::where('user_id',$r->id)->first();
        
            $alltc =User::select(DB::raw('user_id AS USER_ID, name AS NAME, email AS EMAIL, phone AS PHONE, roles.role as DESIGNATION'))
        ->join('roles','users.role', '=', 'roles.id')
        ->where('users.role',6)->where('users.delete',0)->orderBy('users.id', 'DESC')->get();
        
        
        // $i=0;
        // foreach($alltc as $row){
        //     $yo=Team::select(DB::raw('users.name AS LEADER'))
        //     ->join('users','users.user_id', '=', 'teams.tl')->where('teams.tc',$row->USER_ID)->orderBy('id', 'DESC')->get();
        //     if(count($yo) > 0){
        //         $alltc[$i]->LEADER=$yo[0]->LEADER;
        //     }else{
        //         $alltc[$i]->LEADER='';
        //     }
        //     $i++;
        // }
        // // dd($alltc);
        if($alltc) {
            return response()->json($alltc);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
}
