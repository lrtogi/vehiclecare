<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Master\Company;
use App\Models\Master\Worker;
use App\Models\Master\Customer;
use App\Models\User;
use Log;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WorkerController extends Controller
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
        $worker = Worker::where('company_id', $company_id)->get();
        foreach($worker as $w){
            $w['hasAccess'] = $w->user->user_type == 2 ? 'Has Web Access' : 'Standard';
        }
        return view('company.worker.index')
        ->with('pageTitle', "Worker")
        ->with('worker', $worker);
    }

    public function showForm(Request $request, $package_id = null){
        if($package_id == null){
            $model = new Worker();
        }
        else{
            $model = Worker::findOrFail($package_id);
            if($model->company_id != auth()->user()->company_id){
                return redirect()->back()->with('error', 'You are not allowed to edit this data.');
            }
            elseif($model->user_id == null){
                return redirect()->back()->with('error', 'The worker has been deleted before.');
            }
            else{
                $user = User::findOrFail($model->user_id);
                $model['user_type'] = $user->user_type;
            }
        }

        return view('company.worker.form')
        ->with('pageTitle', 'Worker Form')
        ->with('model', $model);
    }

    public function store(Request $request){
        DB::beginTransaction();
        if($request->workerID == null){
            $messages = [
                'required' => ':Attribute can not empty'
            ];
            $this->validate($request, [
                'username' => 'required|string|max:255|unique:users,username',
                'full_name' => 'required|string',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'no_telp' => 'required|numeric',
                'has_access' => 'required|numeric|min:1|max:2',
                'alamat' => 'required'
                ], $messages);
        }
        else{
            $messages = [
                'required' => ':Attribute can not empty'
            ];
            $this->validate($request, [
                'full_name' => 'required|string',
                'no_telp' => 'required|numeric',
                'has_access' => 'required|numeric|min:1|max:2',
                'alamat' => 'required'
                ], $messages);
        }
        $worker_id = $request->input('workerID') == null ? null : $request->workerID;

        if($worker_id == null){
            $user = new User();
            $userID = Str::orderedUuid();
            $user->id = $userID;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->user_type = $request->has_access;
            $user->company_id = auth()->user()->company_id;
            $user->locked = 0;
            $user->save();

            $worker = new Worker();
            $worker->worker_id = Str::orderedUuid();
            $worker->worker_name = $request->full_name;
            $worker->no_telp = $request->no_telp;
            $worker->company_id = auth()->user()->company_id;
            $worker->alamat = $request->alamat;
            $worker->user_id = $userID;
            $worker->approved = 1;
            $worker->created_at = Carbon::now();
            $worker->created_user = $request->username;
            $worker->updated_at = Carbon::now();
            $worker->updated_user = $request->username;
            $worker->save();
        }
        else{
            $worker = Worker::findOrFail($worker_id);
            if($worker->user_id == null){
                return redirect()->back()->with('error', 'The worker has been deleted before.');
            }
            $user = User::findOrFail($worker->user_id);
            $worker->worker_name = $request->full_name;
            $worker->no_telp = $request->no_telp;
            $worker->alamat = $request->alamat;
            $worker->updated_at = Carbon::now();
            $worker->updated_user = $request->username;
            $worker->save();
            $user->user_type = $request->has_access;
            $user->save();
        }

        DB::commit();
        return redirect()->back()->with('success', 'Success save data.');
    }

    public function rejectPayment(Request $request){

    }

    public function approvePayment(Request $request){

    }

    public function getSearch(Request $request, $approve){

    }

}
