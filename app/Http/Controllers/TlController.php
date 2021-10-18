<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TlController extends Controller
{
    public function showTl(Request $r)
    {
        $usr = User::where('user_id',$r->id)->first();
        $alltl = User::select(DB::raw('distinct user_id AS USER_ID, name AS NAME, email AS EMAIL, phone AS PHONE, roles.role as DESIGNATION'))
        ->join('teams','users.user_id', '=', 'teams.tl')->join('roles','users.role', '=', 'roles.id')->
        where('users.role',2)->where('users.delete',0)->where('teams.bm',$r->id)->get();

        if($alltl) {
            return response()->json($alltl);
        }

        return response()->json(['message' => 'Not found!'], 404);
    }
}
