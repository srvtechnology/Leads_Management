<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // return response()->json(['msg'=>$request]);
        // return ($request);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
            
        ]);
        
        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'role' => $request->role,
                'bank'=>$request->bank,
            ]);
            if ($user->role == 1) {
                $id = 'BM' . str_pad($user->id, 3, '0', STR_PAD_LEFT);
            } elseif ($user->role == 2) {
                $id = 'TL' . str_pad($user->id, 3, '0', STR_PAD_LEFT);
            } elseif ($user->role == 3) {
                $id = 'TC' . str_pad($user->id, 3, '0', STR_PAD_LEFT);
            }elseif ($user->role == 4) {
                $id = 'BP' . str_pad($user->id, 3, '0', STR_PAD_LEFT);
            }
            $qty = User::where('id', $user->id)->update(['user_id' => $id]);
            if ($user->role == 2) {
                Team::create(['tl' => $id,'tc' => 'TC','bm' => $request->bm]);
            }elseif($user->role == 3) {
                Team::create(['tl' => 'TL','tc' => $id,'bm' => $request->bm]);
            }
            return response()->json(['user'=>$user,'msg'=>"Account Created Succesfully", 'flag'=>1]);
        }catch(Exception $e){
            return response()->json(['msg'=>$e->getMessage(), 'flag'=>0]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        // dd($credentials);
        try{
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $request->session()->regenerate();
                $token = $user->createToken($user->email . '-' . now());
                $rt = Role::where('id',$user->role)->first();
                $user->myrole = $rt->role;
                return response()->json([
                    'token' => $token->accessToken,
                    'user'=>$user
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'msg' => $e->getMessage(),
                
            ]);
        }
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return response()->json(['msg'=>"Logout Succesfully:)"]);
    }


    public function forgot_password(Request $request)
{
    $input = $request->all();
    $rules = array(
        'email' => "required|email",    
    );
    $arr=[];
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
        $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
    } else {
        try {
            $response = Password::sendResetLink($request->only('email'));
                // dd($response);
            
            switch ($response) {
                case Password::RESET_LINK_SENT:
                    return response()->json(array("status" => 200, "message" => trans($response), "data" => array()));
                case Password::INVALID_USER:
                    return response()->json(array("status" => 400, "message" => trans($response), "data" => array()));
            }
        } catch (\Swift_TransportException $ex) {
            $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
        } catch (Exception $ex) {
            $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
        }
    }
    return response()->json($arr);
}

public function change_password(Request $request)
{
    $input = $request->all();
    $userid = $request->id;
    $rules = array(
        'old_password' => 'required',
        'new_password' => 'required|min:6',
        'confirm_password' => 'required|same:new_password',
    );
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
        $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
    } else {
        try {
            $user = User::where('id',$userid)->first();
            if ((Hash::check(request('old_password'), $user->password)) == false) {
                $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
            } else if ((Hash::check(request('new_password'), $user->password)) == true) {
                $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
            } else {
                User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                $arr = array("status" => 200, "message" => "Password updated successfully:)", "data" => array());
            }
        } catch (Exception $ex) {
            if (isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            } else {
                $msg = $ex->getMessage();
            }
            $arr = array("status" => 400, "message" => $msg, "data" => array());
        }
    }
    return response()->json($arr);
}
}
