<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Models\Master\Company;
use App\Models\User;
use App\Models\Master\Customer;
use App\Models\Master\Worker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompanyController extends Controller
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
    public function index()
    {
        $company = Company::all();

        return view('admin.master.company.index')
                ->with('company', $company)
                ->with('pageTitle', "Company List");
    }

    public function showForm(Request $request, $id = null){
        try{
            if($id == null)
                $model = new Company();
            else
                $model = Company::findOrFail($id);

            return view('admin.master.company.form')
            ->with('pageTitle', "Company Form")
            ->with('model',$model);
        }
        catch(\Exception $e){
            log::debug($e->getMessage().' on line ' . $e->getLine() . ' on File ' . $e->getFile());
            $message = "Vehicle ID Not Found";
            return redirect()->back()->with('error', $message);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        if($request->useExistingEmail1 == 1 && $request->useExistingEmail2 == 1){
            $messages = [
                'required' => ':Attribute can not empty'
            ];
            $this->validate($request, [
                'email' => 'required|string|email|max:255|unique:users',
                'company_name' => 'required',
                'no_telp_company' => 'required|numeric',
                'alamat_perusahaan' => 'required'
                ], $messages);
        }
        else{
            $messages = [
                'required' => ':Attribute can not empty'
            ];
            $this->validate($request, [
                'username' => 'required|string|max:255|unique:users,username',
                'full_name' => 'required|string',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'company_name' => 'required',
                'no_telp' => 'required|numeric',
                'no_telp_company' => 'required|numeric',
                'alamat_perusahaan' => 'required'
                ], $messages);
        }

        $company = new Company();
        $company->company_id = Str::orderedUuid();
        $company->company_name = $request->company_name;
        $company->pic_email = $request->email;
        $company->no_telp = $request->no_telp_company;
        $company->alamat_perusahaan = $request->alamat_perusahaan;
        $company->active = 1;
        $company->approved = 1;
        $company->created_at = Carbon::now();
        $company->created_user = auth()->user()->username;
        $company->updated_at = Carbon::now();
        $company->updated_user = auth()->user()->username;
        if(!$company->save()){
            DB::rollback();
            $error = "An Error occured while saving company data.";
            return redirect()->back()->with('error', $error);
        }
        
        if($request->useExistingEmail1 == 0 && $request->useExistingEmail2 == 0){
            $user = new User();
            $uuidUser = Str::orderedUuid();
            $user->id = $uuidUser;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->user_type = 2;
            $user->company_id = $company->company_id;
            $user->locked = 0;
            $user->created_at = Carbon::now();
            $user->updated_at = Carbon::now();
            if(!$user->save()){
                log::debug("error user add");
                DB::rollback();
                $error = "An Error occured while saving company data.";
                return redirect()->back()->with('error', $error);
            }
        }
        else{
            $user = User::where('email', $request->email)->first();
            $uuidUser = $user->id;
        }

        $worker = new Worker();
        $worker->worker_id = Str::orderedUuid();
        $worker->worker_name = $request->full_name;
        $worker->company_id = $company->company_id;
        $worker->no_telp = $request->no_telp;
        $worker->created_at = Carbon::now();
        $worker->created_user = $request->username;
        $worker->updated_at = Carbon::now();
        $worker->updated_user = $request->username;
        $worker->user_id = $uuidUser;
        if(!$worker->save()){
            log::debug('error worker add');
            DB::rollback();
            $error = "Error while saving data worker.";
            return redirect()->back()->with('error', $error);
        }

        if($request->useExistingEmail1 == 0 && $request->useExistingEmail2 == 0){
            $customer = new Customer();
            $customer->customer_id = Str::orderedUuid();
            $customer->customer_name = $request->full_name;
            $customer->no_telp = $request->no_telp;
            $customer->created_at = Carbon::now();
            $customer->created_user = $request->username;
            $customer->updated_at = Carbon::now();
            $customer->updated_user = $request->username;
            $customer->user_id = $uuidUser;
            if(!$customer->save()){
                log::debug('error customer add');
                DB::rollback();
                $error = "Error while saving data customer.";
                return redirect()->back()->with('error', $error);
            }
        }
        DB::commit();
        $message = "Success Save Company.";
        return redirect()->back()->with('success', $message);
        
    }

    public function void(Request $request)
    {
        $messages = [
            'company_id.required' => ':Attribute can not empty'
        ];
        $this->validate($request, [
            'company_id' => 'required'
            ], $messages);
        DB::beginTransaction();
        $company = Company::findOrFail($request->company_id);
        $company->active = 0;
        $company->save();
        DB::commit();
        return redirect()->back()->with([
            'success' => "Success deactivate company."
        ]);
    }

    public function unvoid(Request $request)
    {
        $messages = [
            'company_id.required' => ':Attribute can not empty'
        ];
        $this->validate($request, [
            'company_id' => 'required'
            ], $messages);
        DB::beginTransaction();
        $company = Company::findOrFail($request->company_id);
        $company->active = 1;
        $company->save();
        DB::commit();
        return redirect()->back()->with([
            'success' => "Success activate company."
        ]);
    }

    public function getUser(Request $request, $user_type){
        $users = User::where('locked', 0)->whereNull('company_id')->where('user_type', $user_type)->get();
        return response()->json([
            'result' => true,
            'data' => $users
        ]);
    }
}
