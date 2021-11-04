<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Master\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;

class APIController extends Controller
{
    public function register(Request $request)
    {
        log::debug('masuk');
        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'cpassword' => 'required|same:password',
                'no_telp' => 'required|numeric',
                'fullname' => 'required',
                'address' => 'required'
            ]);
    
            if($validator->fails()){
                return response()->json(['result' => false, 'message' => $validator->errors()]);       
            }

            $findUser = User::where(function($query) use($request){
                $query->orWhere('username', $request->username);
                $query->orWhere('email', $request->email);
            })->count();
            if($findUser > 0){
                return response()->json(['result' => false, 'message' => 'Username or email has already been used']);       
            }

            $user = User::create([
                'id' => Str::orderedUuid(),
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => 1,
                'locked' => 0
            ]);

            $customer = new Customer();
            $customer->customer_id = Str::orderedUuid();
            $customer->customer_name = $request->fullname;
            $customer->alamat = $request->address;
            $customer->no_telp = $request->no_telp;
            $customer->created_user = $user->username;
            $customer->updated_user = $user->username;
            $customer->user_id = $user->id;
            $customer->save();

            // $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();
            return response()->json([
                'result' => true,
                'message' => 'Register Successfully'
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
        try{
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user();
                $customer = Customer::where('user_id', $user->id)->first();
                $success['result'] = true;
                $success['message'] = 'Logged In Successfully';
                if($user->user_type == 0){
                    $success['token'] =  $user->createToken('auth_token', ['role:customer'])->plainTextToken; 
                }
                elseif($user->user_type == 1)
                    $success['token'] =  $user->createToken('auth_token', ['role:worker'])->plainTextToken;
                elseif($user->user_type == 2)
                    $success['token'] =  $user->createToken('auth_token', ['role:company'])->plainTextToken;
                elseif($user->user_type == 3)
                    $success['token'] =  $user->createToken('auth_token', ['role:admin'])->plainTextToken;
                $success['name'] =  $customer->customer_name;
                $success['username'] = $user->username;
                $success['email'] = $user->email;
                $success['user_id'] = $user->id;
                return response()->json($success);
            } 
            else{
                return response()->json(['result' => false, 'message' => 'Invalid login attempt']);
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
        catch(\Exception $e){
            log::debug($e->getMessage() . " on line ". $e->getLine() . " on file ". $e->getFile());
        }
    }
}
