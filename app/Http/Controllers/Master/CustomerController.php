<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Models\Master\Company;
use App\Models\User;
use App\Models\Master\Customer;
use App\Models\Master\CustomerVehicle;
use App\Models\Master\Worker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use DB;
use Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProfile()
    {
        $customer = User::join('m_customer', 'm_customer.user_id', 'users.id')->where('id',auth()->user()->id)->first();
        $result = [
            'result' => true,
            'message' => 'Success get data',
            'model' => $customer
        ];
        return response()->json($result);
    }

    public function saveProfile(Request $request){
        DB::beginTransaction();
        try{
            $user = User::find(auth()->user()->id);
            $user->email = $request->email;
            $user->save();

            $customer = Customer::where('user_id', $user->id)->first();
            $customer->customer_name = $request->name;
            $customer->no_telp = $request->no_telp;
            $customer->alamat = $request->address;
            $customer->save();

            $result = [
                'result' => true,
                'message' => 'Success Saving profile'
            ];
            DB::commit();
            return response()->json($result);
        }
        catch(\Exception $e){
            DB::rollback();
            log::debug($e->getMessage() . " on line " . $e->getLine() . " on file ". $e->getFile());
            return response()->json([
                'result' => false,
                'message' => 'An Error occured while saving data'
            ]);
        }
    }

    public function getVehicle(Request $request){
        $customerVehicle = CustomerVehicle::where('customer_id', $request->customer_id)->get();
        $result = [
            'result' => true,
            'message' => 'Success get data',
            'data' => $customerVehicle
        ];
        return response()->json($result);
    }

    public function saveVehicle(Request $request){
        DB::beginTransaction();
        try{
            $customer = Customer::find($request->customer_id);
            $customer_vehicle_id = $request->input('customer_vehicle_id') != null ? $request->customer_vehicle_id : '';
            if($customer_vehicle_id != null){
                $customerVehicle = CustomerVehicle::find($customer_vehicle_id);
            }
            else{
                $customerVehicle = new CustomerVehicle();
                $customerVehicle->customer_vehicle_id = Str::orderedUuid();
                $customerVehicle->customer_id = $customer->customer_id;
                $customerVehicle->customer_name= $customer->customer_name;
                $customerVehicle->created_user = auth()->user()->username;
            }
            $customerVehicle->vehicle_name = $request->vehicle_name;
            $customerVehicle->police_number = $request->police_number;
            $customerVehicle->updated_user = auth()->user()->username;
            $customerVehicle->save();

            $result = [
                'result' => true,
                'message' => 'Success saving vehicle datas',
                'model' => $customerVehicle
            ];
            return response()->json($result);
        }
        catch(\Exception $e){
            log::debug($e->getMessage() . " on line " . $e->getLine() . " on file " . $e->getFile());
            return response()->json([
                'result' => false,
                'message' => "Error while saving data"
            ]);
        }
    }
}
