<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Master\Vehicle;
use Log;
use DB;
use Carbon\Carbon;

class VehicleController extends Controller
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
        $vehicle = Vehicle::all();
        return view('admin.master.vehicle.index')
        ->with('pageTitle', "Master Data Vehicle Type")
        ->with('vehicle', $vehicle);
    }

    public function showForm(Request $request, $id = null){
        try{
            if($id == null)
                $model = new Vehicle();
            else
                $model = Vehicle::findOrFail($id);

            return view('admin.master.vehicle.form')
            ->with('pageTitle', "Data Vehicle")
            ->with('model',$model);
        }
        catch(\Exception $e){
            log::debug($e->getMessage().' on line ' . $e->getLine() . ' on File ' . $e->getFile());
            $message = "Vehicle ID Not Found";
            return redirect()->back()->with('error', $message);
        }
    }

    public function store(Request $request){
        DB::beginTransaction();
        $messages = [
            'vehicle_type.required' => ':Attribute can not empty'
        ];
        $this->validate($request, [
            'vehicle_type' => 'required'
            ], $messages);
        if($request->vehicleID != null){
            $vehicle = Vehicle::findOrFail($request->vehicleID);
        }
        else{
            $vehicle = new Vehicle();
            $vehicle->vehicle_id = Str::orderedUuid();
            $vehicle->created_user = auth()->user()->username;
            $vehicle->created_at = Carbon::now();
        }
        $vehicle->vehicle_type = $request->vehicle_type;
        $vehicle->updated_user = auth()->user()->username;
        $vehicle->updated_at = Carbon::now();
        if(!$vehicle->save()){
            DB::rollback();
            return redirect()->back()->with([
                'error' => 'Error while savind vehicle'
            ]);
        }
        DB::commit();
        return redirect()->back()->with([
            'success' => 'Success saving data.'
        ]);
    }

    public function delete(Request $request){
        DB::beginTransaction();
        $messages = [
            'vehicle_id.required' => ':Attribute can not empty'
        ];
        $this->validate($request, [
            'vehicle_id' => 'required'
            ], $messages);
        $vehicle = Vehicle::where('vehicle_id',$request->vehicle_id)->delete();
        DB::commit();
        return redirect()->back()->with([
            'success' => 'Success delete data.'
        ]);
    }

}
