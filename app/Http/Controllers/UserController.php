<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }
    
    public function show($userId)
    {
        $user = User::find($userId);

        if($user) {
            return response()->json($user);
        }

        return response()->json(['message' => 'User not found!'], 404);
    }
    public function update(Request $request){
        
        $res = User::where('id',$request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => $request->role,
                'aadhar'=>$request->aadhar,
                'pan'=>$request->pan
        ]);
        $user = User::where('id',$request->id)->first();
        $rt = Role::where('id',$user->role)->first();
            $user->myrole = $rt->role;
        return response()->json(['user'=>$user,'msg'=>"Updated Succesfully:)"]);
    }
    public static function save_id_proof(Request $request)
    {

        try {

            $file = $request->file;
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('/emp_files');
            $file->move($destinationPath, $file_name);
            User::where('id', $request->id)->update(['id_proof' => $file_name]);
            $data = User::where('id',$request->id)->first();
            return response()->json(['msg' => "File uploaded",'user'=>$data]);
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }
    public static function delete(Request $request){
        try{
            User::where('user_id',$request->id)->update(['delete' => 1]);
            return response()->json(['msg'=>"User Deleted"]);
        }catch(Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
 
    }


    public static function save_image(Request $request)
    {
        
        try{

            $file = $request->image;
                $file_name = time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('/uploads');
                $file->move($destinationPath, $file_name);
                User::where('id',$request->id)->update(['avatar' => $file_name]);
                $user = User::where('id',$request->id)->first();
                return response()->json(['msg'=> "Image uploaded",'user'=>$user]);
        }catch(Exception $e){
            return response()->json(['msg'=> $e->getMessage()]);
        }
        
    }

    
}
