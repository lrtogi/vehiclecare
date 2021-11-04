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
use App\Models\Master\PaymentMethod;
use App\Models\User;
use Log;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
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
        $paymentMethod = PaymentMethod::where('company_id', $company_id)->get();
        return view('company.paymentMethod.index')
            ->with('pageTitle', "Payment Method List")
            ->with('paymentMethod', $paymentMethod);
    }

    public function showForm(Request $request, $payment_method_id = null)
    {
        if ($payment_method_id == null) {
            $model = new PaymentMethod();
        } else {
            $model = PaymentMethod::where('payment_method_id', $payment_method_id)->first();
            if ($model->company_id != auth()->user()->company_id) {
                return redirect()->back()->with('error', 'You do not have access to edit this data');
            }
        }

        return view('company.paymentMethod.form')
            ->with('model', $model)
            ->with('pageTitle', 'Payment Method Form');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'method' => 'required',
                'value' => 'required',
                'on_behalf_of' => 'required'
            ]);

            $payment_method_id = $request->paymentMethodID != null ? $request->paymentMethodID : null;
            if ($payment_method_id == null) {
                $paymentMethod = new PaymentMethod();
                $paymentMethod->payment_method_id = Str::orderedUuid();
                $paymentMethod->company_id = auth()->user()->company_id;
                $paymentMethod->void = 0;
                $paymentMethod->created_user = auth()->user()->username;
            } else {
                $paymentMethod = PaymentMethod::findOrFail($payment_method_id);
                if ($paymentMethod->company_id != auth()->user()->company_id) {
                    return redirect()->back()->with('error', 'You do not have access to edit this data');
                }
            }
            $paymentMethod->method = $request->method;
            $paymentMethod->value = $request->value;
            $paymentMethod->on_behalf_of = $request->on_behalf_of;
            $paymentMethod->updated_user = auth()->user()->username;
            $paymentMethod->save();

            DB::commit();
            return redirect()->back()->with('success', 'Success save data.');
        } catch (\Exception $e) {
            log::debug($e->getMessage() . " on line " . $e->getLine() . ' on file ' . $e->getFile());
            return redirect()->back()->with('error', 'Error while saving data.')->withInput();
        }
    }

    public function void(Request $request)
    {
        DB::beginTransaction();
        try {
            $company_id = auth()->user()->company_id;
            $paymentMethod = PaymentMethod::where('company_id', $company_id)->where('payment_method_id', $request->payment_method_id)->first();
            $paymentMethod->void = 1;
            $paymentMethod->save();
            DB::commit();

            return redirect()->back()->with('success', 'Success void data.');
        } catch (\Exception $e) {
            log::debug($e->getMessage() . " on line " . $e->getLine() . ' on file ' . $e->getFile());
            return redirect()->back()->with('error', 'Error while saving data.');
        }
    }

    public function unvoid(Request $request)
    {
        DB::beginTransaction();
        try {
            $company_id = auth()->user()->company_id;
            $paymentMethod = PaymentMethod::where('company_id', $company_id)->where('payment_method_id', $request->payment_method_id)->first();
            $paymentMethod->void = 0;
            $paymentMethod->save();
            DB::commit();

            return redirect()->back()->with('success', 'Success unvoid data.');
        } catch (\Exception $e) {
            log::debug($e->getMessage() . " on line " . $e->getLine() . ' on file ' . $e->getFile());
            return redirect()->back()->with('error', 'Error while saving data.');
        }
    }

    public function getByCompany(Request $request)
    {
        $paymentMethod = PaymentMethod::where('company_id', $request->company_id)->where('void', 0)->get();
        foreach ($paymentMethod as $pm) {
            $pm['isExpanded'] = false;
        }

        $result = [
            'result' => true,
            'data' => $paymentMethod
        ];
        return response()->json($result);
    }
}
