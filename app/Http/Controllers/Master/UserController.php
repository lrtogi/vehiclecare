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

class UserController extends Controller
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
        $user = User::all();
        return view('admin.master.user.index')
            ->with('pageTitle', "Users")
            ->with('user', $user);
    }

    public function showForm(Request $request, $id = null)
    {
        try {
            if ($id == null)
                $model = new User();
            else
                $model = User::join('m_customer', 'm_customer.user_id', 'users.id')->where('id', $id)->first();
            // dd($model->company_id);
            $company = Company::where('active', 1)->get();
            return view('admin.master.user.form')
                ->with('pageTitle', "User Form")
                ->with('model', $model)
                ->with('company', $company);
        } catch (\Exception $e) {
            log::debug($e->getMessage() . ' on line ' . $e->getLine() . ' on File ' . $e->getFile());
            $message = "User ID Not Found";
            return redirect()->back()->with('error', $message);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        if ($request->user_id == null) {
            $messages = [
                'required' => ':Attribute can not empty'
            ];
            $this->validate($request, [
                'username' => 'required|string|max:255|unique:users,username',
                'full_name' => 'required|string',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'no_telp' => 'required|numeric',
                'user_type' => 'required',
                'alamat' => 'required'
            ], $messages);
        } else {
            $messages = [
                'required' => ':Attribute can not empty'
            ];
            $this->validate($request, [
                'username' => 'required|string|max:255',
                'full_name' => 'required|string',
                'email' => 'required|string|email|max:255',
                'no_telp' => 'required|numeric',
                'user_type' => 'required',
                'alamat' => 'required'
            ], $messages);
        }
        if ($request->user_type == 1 || $request->user_type == 2) {
            $this->validate($request, [
                'company_id' => 'required'
            ]);
        }
        if ($request->user_id != null) {
            $user = User::findOrFail($request->user_id);
            if ($request->user_type == 0) {
                if ($user->user_type == 1) {
                    $worker = Worker::where('user_id', $user->id)->delete();
                } elseif ($user->user_type == 2) {
                    $checkCompany = Company::where('pic_email', $user->email);
                    if ($checkCompany->count() > 0) {
                        $checkCompany = $checkCompany->first();
                        $checkCompany->active = 0;
                        $checkCompany->save();
                        $worker = Worker::where('company_id', $user->company_id)->delete();
                    }
                }
                $user->company_id = null;
            } elseif ($request->user_type == 1) {
                $userCompany = Worker::where('user_id', $user->user_id)->first();
                if ($request->company_id != $userCompany->company_id) {
                    $userCompany->company_id = $request->company_id;
                    $userCompany->save();
                }
                if ($user->user_type == 2) {
                    $checkCompany = Company::where('pic_email', $user->email);
                    if ($checkCompany->count() > 0) {
                        $checkCompany = $checkCompany->first();
                        $checkCompany->active = 0;
                        $checkCompany->save();
                    }
                    $user->company_id = $request->company_id;
                }
            } elseif ($request->user_type == 2 && $user->user_type == 2) {
                if ($user->company_id != $request->company_id) {
                    $checkCompany = Company::where('pic_email', $user->email);
                    if ($checkCompany->count() > 0) {
                        $checkCompany = $checkCompany->first();
                        $checkCompany->active = 0;
                        $checkCompany->save();
                    }
                    $userCompany = Worker::where('user_id', $user->user_id)->first();
                    if ($request->company_id != $userCompany->company_id) {
                        $userCompany->company_id = $request->company_id;
                        $userCompany->save();
                    }
                }
            }
        } else {
            $user = new User();
            $uuidUser = Str::orderedUuid();
            $user->id = $uuidUser;
            $user->password = Hash::make($request->password);
        }
        $user->username = $request->username;
        $user->email = $request->email;
        $user->user_type = $request->user_type;
        if ($request->user_type != 0 && $request->user_type != 3) {
            $user->company_id = $request->company_id;
        }
        $user->locked = $request->locked;
        $user->created_at = Carbon::now();
        $user->updated_at = Carbon::now();
        if (!$user->save()) {
            log::debug("error user add");
            DB::rollback();
            $error = "An Error occured while saving company data.";
            return redirect()->back()->with('error', $error);
        }
        if ($request->user_id == null) {
            if ($request->user_type == 1) {
                $worker = new Worker();
                $worker->worker_id = Str::orderedUuid();
                $worker->worker_name = $request->full_name;
                $worker->company_id = $request->company_id;
                $worker->active = 1;
                $worker->approved = 1;
                $worker->no_telp = $request->no_telp;
                $worker->created_at = Carbon::now();
                $worker->created_user = $request->username;
                $worker->updated_at = Carbon::now();
                $worker->updated_user = $request->username;
                $worker->user_id = $uuidUser;
                if (!$worker->save()) {
                    log::debug('error worker add');
                    DB::rollback();
                    $error = "Error while saving data worker.";
                    return redirect()->back()->with('error', $error);
                }
            }
            $customer = new Customer();
            $customer->customer_id = Str::orderedUuid();
            $customer->customer_name = $request->full_name;
            $customer->no_telp = $request->no_telp;
            $customer->created_at = Carbon::now();
            $customer->created_user = $request->username;
            $customer->updated_at = Carbon::now();
            $customer->updated_user = $request->username;
            $customer->user_id = $uuidUser;
            $customer->alamat = $request->alamat;
            if (!$customer->save()) {
                log::debug('error customer add');
                DB::rollback();
                $error = "Error while saving data customer.";
                return redirect()->back()->with('error', $error);
            }
        }


        DB::commit();
        return redirect()->back()->with([
            'success' => 'Success saving data.'
        ]);
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        $messages = [
            'user_id.required' => ':Attribute can not empty'
        ];
        $this->validate($request, [
            'user_id' => 'required'
        ], $messages);
        $vehicle = User::where('id', $request->user_id)->delete();
        DB::commit();
        return redirect()->back()->with([
            'success' => 'Success delete data.'
        ]);
    }

    public function changePasswordForm()
    {

        return view('company.changePassword')
            ->with('pageTitle', "Change Password");
    }

    public function changePasswordFormAdmin()
    {

        return view('admin.profile.changepassword')
            ->with('pageTitle', "Change Password");
    }

    public function changePassword(Request $request)
    {
        DB::beginTransaction();
        $messages = [
            'required' => ':Attribute can not empty'
        ];
        $this->validate($request, [
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ], $messages);
        $user = User::find(auth()->user()->id);
        $checkPass = Hash::check($request->old_password, $user->password);
        if (!$checkPass) {
            return redirect()->back()->with('error', 'Incorrect old password');
        }

        $user->password = Hash::make($request->password);
        $user->save();
        DB::commit();

        return redirect()->back()->with('success', 'Success change password');
    }
}
