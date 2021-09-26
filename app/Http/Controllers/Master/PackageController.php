<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Master\Company;
use App\Models\Master\Worker;
use App\Models\Master\Customer;
use App\Models\Master\Package;
use App\Models\Master\Vehicle;
use App\Models\User;
use Log;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $company_id = auth()->user()->company_id;
        $package = Package::where('company_id', $company_id)->get();
        return view('company.package.index')
        ->with('pageTitle', "Package List")
        ->with('package', $package);
    }

    public function showForm(Request $request, $package_id){
        if($package_id == null){
            $model = new Package();
        }
        else{
            $model = Package::where('package_id', $package_id)->first();
            if($model->company_id != auth()->user()->company_id){
                return redirect()->back()->with('error', 'You do not have access to edit this data');
            }
        }
        $vehicleType = Vehicle::all();
        return view('company.package.form')
        ->with('model', $model)
        ->with('vehicleType', $vehicleType)
        ->with('pageTitle', 'Package Form');
    }

    public function store(Request $request){
        DB::beginTransaction();
        try{
            $this->validate($request, [
                'package_name' => 'required',
                'vehicle_type' => 'required',
                'price' => 'required|min:0',
                'discount_percentage' => 'required|between:0,100',
                'discounted_price' => 'required|min:0',
                'active' => 'required|between:0,1',
                ]);

            $package_id = $request->packageID != null ? $request->packageID : null;
            if($package_id == null){
                $package = new Package();
                $package->package_id = Str::orderedUuid();
                $package->company_id = auth()->user()->company_id;
            }
            else{
                $package = Package::findOrFail($package_id);
                if($package->company_id != auth()->user()->company_id){
                    return redirect()->back()->with('error', 'You do not have access to edit this data');
                }
            }
            $package->package_name =$request->package_name;
            $package->price = (double)$request->price;
            $package->vehicle_id = $request->vehicle_type;
            $package->discount_percentage = (double)$request->discount_percentage;
            $package->discounted_price = (double)$package->price - ((double)$package->price * (double)$package->discount_percentage / 100);
            $package->active = $request->active;
            $package->save();

            DB::commit();
            return redirect()->back()->with('success', 'Success save data.');
        }
        catch(\Exception $e){
            log::debug($e->getMessage() . " on line ". $e->getLine() . ' on file ' . $e->getFile());
            return redirect()->back()->with('error', 'Error while saving data.');
        }
    }

}
