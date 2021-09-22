<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Master\Company;
use App\Models\Master\Worker;
use App\Models\Master\Customer;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;
use Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'full_name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_name' => ['required'],
            'username' => ['required','unique:users,username'],
            'no_telp' => ['required','numeric'],
            'no_telp_company' => ['required','numeric'],
            'alamat' => ['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $request)
    {
        DB::beginTransaction();
        $company = new Company();
        $company->company_id = Str::orderedUuid();
        $company->company_name = $request['company_name'];
        $company->pic_email = $request['email'];
        $company->no_telp = $request['no_telp_company'];
        $company->alamat_perusahaan = $request['alamat'];
        $company->active = 0;
        $company->approved = 0;
        $company->created_at = Carbon::now();
        $company->created_user = $request['username'];
        $company->updated_at = Carbon::now();
        $company->updated_user = $request['username'];
        if(!$company->save()){
            DB::rollback();
            $error = "An Error occured while saving company data.";
            return redirect()->back()->with('error', $error);
        }

        $user = new User();
        $uuidUser = Str::orderedUuid();
        $user->id = $uuidUser;
        $user->username = $request['username'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
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

        $worker = new Worker();
        $worker->worker_id = Str::orderedUuid();
        $worker->worker_name = $request['full_name'];
        $worker->company_id = $company->company_id;
        $worker->created_at = Carbon::now();
        $worker->created_user = $request['username'];
        $worker->updated_at = Carbon::now();
        $worker->updated_user = $request['username'];
        $worker->user_id = $uuidUser;
        if(!$worker->save()){
            log::debug('error worker add');
            DB::rollback();
            $error = "Error while saving data worker.";
            return redirect()->back()->with('error', $error);
        }

        $customer = new Customer();
        $customer->customer_id = Str::orderedUuid();
        $customer->customer_name = $request['full_name'];
        $customer->no_telp = $request['no_telp'];
        $customer->created_at = Carbon::now();
        $customer->created_user = $request['username'];
        $customer->updated_at = Carbon::now();
        $customer->updated_user = $request['username'];
        $customer->user_id = $uuidUser;
        if(!$customer->save()){
            log::debug('error customer add');
            DB::rollback();
            $error = "Error while saving data customer.";
            return redirect()->back()->with('error', $error);
        }

        DB::commit();
        return $user;
    }

    protected function redirectTo()
    {
        $register="success";
        return route('login',compact('register'));
    }
}
