<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use DB;

class APIController extends Controller
{
    public function register(Request $request)
    {
        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'cpassword' => 'required|same:password',
            ]);
    
            if($validator->fails()){
                return response()->json(['result' => false, 'message' => $validator->errors()]);       
            }

            $findUser = User::where('email', $request->email)->count();
            if($findUser > 0){
                return response()->json(['result' => false, 'message' => ['email' => ['Duplicated email']]]);       
            }

            $user = User::create([
                'id' => Str::orderedUuid(),
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => 1
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }
        catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'result' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['result' => false, 'message' => $validator->errors()]);       
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user();
            if($user->user_type == 0){
                $success['token'] =  $user->createToken('auth_token', ['role:customer'])->plainTextToken; 
            }
            elseif($user->user_type == 1)
                $success['token'] =  $user->createToken('auth_token', ['role:worker'])->plainTextToken;
            elseif($user->user_type == 2)
                $success['token'] =  $user->createToken('auth_token', ['role:company'])->plainTextToken;
            elseif($user->user_type == 3)
                $success['token'] =  $user->createToken('auth_token', ['role:admin'])->plainTextToken;
            $success['name'] =  $user->name;
            return response()->json(['result' => true, $success]);
        } 
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 

        $findUser = User::where('email', $request->email)->count();
        if($findUser > 0){
            return response()->json(['result' => false, 'message' => ['email' => ['Duplicated email']]]);       
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
