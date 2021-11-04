<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Models\Master\Company;
use App\Models\User;
use App\Models\Master\Customer;
use App\Models\Master\CustomerVehicle;
use App\Models\Master\Worker;
use App\Models\Master\Vehicle;
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
        $customer = User::join('m_customer', 'm_customer.user_id', 'users.id')->where('id', auth()->user()->id)->first();
        $result = [
            'result' => true,
            'message' => 'Success get data',
            'model' => $customer
        ];
        return response()->json($result);
    }

    public function changePassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth()->user()->id);
            $currenPassword = $request->current_password;
            $newPassword = $request->new_password;

            $hashCheck = Hash::check($currenPassword, $user->password);

            if (!$hashCheck) {
                $result = [
                    'result' => false,
                    'message' => 'Current Password is incorrect'
                ];
                return response()->json($result);
            }

            $hashPassword = Hash::make($newPassword);
            $user->password = $hashPassword;
            $user->save();
            DB::commit();
            $result = [
                'result' => true,
                'message' => 'Success Change Password'
            ];
            return response()->json($result);
        } catch (\Exception $e) {
            DB::rollback();
            log::debug($e->getMessage() . " on line " . $e->getLine() . " on file " . $e->getFile());
            $result = [
                'result' => false,
                'message' => 'Error Occured while change password'
            ];
            return response()->json($result);
        }
    }

    public function saveProfile(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth()->user()->id);
            $user->email = $request->email;
            $user->save();

            $customer = Customer::where('user_id', $user->id)->first();
            $customer->customer_name = $request->name;
            $customer->no_telp = $request->no_telp;
            $customer->alamat = $request->address;
            $customer->save();

            $customerVehicle = CustomerVehicle::where('customer_id', $customer->customer_id)->update(['customer_name' => $customer->customer_name]);

            $result = [
                'result' => true,
                'message' => 'Success Saving profile'
            ];
            DB::commit();
            return response()->json($result);
        } catch (\Exception $e) {
            DB::rollback();
            log::debug($e->getMessage() . " on line " . $e->getLine() . " on file " . $e->getFile());
            return response()->json([
                'result' => false,
                'message' => 'An Error occured while saving data'
            ]);
        }
    }

    public function getVehicle(Request $request)
    {
        $customerVehicle = CustomerVehicle::join('m_vehicle', 'm_vehicle.vehicle_id', 'm_customer_vehicle.vehicle_id')->where('customer_id', $request->customer_id)->get();
        $result = [
            'result' => true,
            'message' => 'Success get data',
            'data' => $customerVehicle
        ];
        return response()->json($result);
    }

    public function getVehicleDetail(Request $request)
    {
        $customerVehicle = CustomerVehicle::where('customer_vehicle_id', $request->customer_vehicle_id)->first();
        $result = [
            'result' => true,
            'message' => 'Success get data',
            'data' => $customerVehicle
        ];
        return response()->json($result);
    }

    public function saveVehicle(Request $request)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::find($request->customer_id);
            $customer_vehicle_id = $request->input('customer_vehicle_id') != null ? $request->customer_vehicle_id : '';
            if ($customer_vehicle_id != null) {
                $customerVehicle = CustomerVehicle::find($customer_vehicle_id);
            } else {
                $customerVehicle = new CustomerVehicle();
                $customerVehicle->customer_vehicle_id = Str::orderedUuid();
                $customerVehicle->customer_id = $customer->customer_id;
                $customerVehicle->customer_name = $customer->customer_name;
                $customerVehicle->created_user = auth()->user()->username;
            }
            $customerVehicle->vehicle_id = $request->vehicle_id;
            $customerVehicle->vehicle_name = $request->vehicle_name;
            $customerVehicle->police_number = $request->police_number;
            $customerVehicle->updated_user = auth()->user()->username;
            if ($request->file('vehicle_photo_url') != null) {
                // $picData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
                // file_put_contents($_SERVER['DOCUMENT_ROOT']."$servicePath"."$path", $picData);
                $file = $request->file('vehicle_photo_url');
                $name = "$customerVehicle->customer_vehicle_id.png";
                $customerVehicle->vehicle_photo_url = "vehicle_photo/$name";
                $path = $_SERVER['DOCUMENT_ROOT'] . "/vehiclecare/public/images/vehicle_photo/";
                $file->move($path, $name);
            } else {
                $path = $_SERVER['DOCUMENT_ROOT'] . "/vehiclecare/public/images/vehicle_photo/";
                if (file_exists($path . $customerVehicle->customer_vehicle_id . ".png"))
                    unlink($path . $customerVehicle->customer_vehicle_id . ".png");
                $customerVehicle->vehicle_photo_url = null;
            }
            $customerVehicle->save();

            $result = [
                'result' => true,
                'message' => 'Success saving vehicle datas',
                'model' => $customerVehicle
            ];
            DB::commit();
            return response()->json($result);
        } catch (\Exception $e) {
            DB::rollback();
            log::debug($e->getMessage() . " on line " . $e->getLine() . " on file " . $e->getFile());
            return response()->json([
                'result' => false,
                'message' => "Error while saving data"
            ]);
        }
    }

    public function deleteVehicle(Request $request)
    {
        DB::beginTransaction();
        try {
            $path = $_SERVER['DOCUMENT_ROOT'] . "/vehiclecare/public/images/vehicle_photo/";
            $customerVehicle = CustomerVehicle::where('customer_vehicle_id', $request->customer_vehicle_id);
            if (file_exists($path . $customerVehicle->first()->customer_vehicle_id . ".png"))
                unlink($path . $customerVehicle->first()->customer_vehicle_id . ".png");
            $customerVehicle->delete();
            $result = [
                'result' => true,
                'message' => 'Success Delete vehicle data'
            ];
            DB::commit();
            return response()->json($result);
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'result' => false,
                'message' => 'Error while deleting data'
            ];
            return response()->json($result);
        }
    }

    public function getType(Request $request)
    {
        $vehicle = Vehicle::get();

        $result = [
            'result' => true,
            'message' => 'Success retrieve data',
            'data' => $vehicle
        ];
        return response()->json($result);
    }
}
