<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Master\Company;
use App\Models\Master\Worker;
use App\Models\Master\Customer;
use App\Models\Master\PaymentMethod;
use App\Models\Transaction\Payment;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\Job;
use App\Models\User;
use Log;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
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
        return view('company.payment.index')
            ->with('pageTitle', "Payment List");
    }

    public function rejectPayment(Request $request)
    {
        DB::beginTransaction();
        try {
            $company_id = auth()->user()->company_id;
            $checkPayment = Payment::join('transactions', 'transactions.transaction_id', 'payments.transaction_id')->where('payments.payment_id', $request->payment_id)->first();
            if ($checkPayment->company_id != $company_id || $checkPayment == null) {
                return redirect()->back()->with('error', 'Error while reject payment');
            }
            $payment = Payment::findOrFail($request->payment_id);
            $transaction = Transaction::findOrFail($payment->transaction_id);
            $transaction->status = 3;
            $transaction->save();
            $payment->approved = 2;
            $payment->save();

            DB::commit();
            return redirect()->back()->with('success', 'Success Reject data');
        } catch (\Exception $e) {
            DB::rollback();
            log::debug($e->getMessage() . " on line " . $e->getLine() . " on file " . $e->getFile());
            return redirect()->back()->with('error', 'Error while reject payment.');
        }
    }

    public function approvePayment(Request $request)
    {
        DB::beginTransaction();
        try {
            $company_id = auth()->user()->company_id;
            $checkPayment = Payment::join('transactions', 'transactions.transaction_id', 'payments.transaction_id')->where('payments.payment_id', $request->payment_id)->first();
            if ($checkPayment->company_id != $company_id || $checkPayment == null) {
                return redirect()->back()->with('error', 'Error while approve payment');
            }
            $payment = Payment::findOrFail($request->payment_id);
            $transaction = Transaction::findOrFail($payment->transaction_id);
            if ($request->approval_type == 4) {
                $transaction->status = 4;
            } else {
                $transaction->status = 2;
                $job = new Job();
                $job->transaction_id = $transaction->transaction_id;
                $countIndex = Job::join('transactions', 'transactions.transaction_id', 'jobs.transaction_id')->where('transactions.order_date', $transaction->order_date)->orderBy('jobs.index', 'desc')->select(['jobs.index'])->first();
                if ($countIndex == null) {
                    $job->index = 1;
                } else {
                    $job->index = $countIndex->index + 1;
                }
                $job->status = 0;
                $job->created_user = auth()->user()->username;
                $job->updated_user = auth()->user()->username;
                if (!$job->save()) {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Job Has been created before.');
                }
            }
            $transaction->save();
            $payment->approved = 1;
            $payment->save();

            DB::commit();
            return redirect()->back()->with('success', 'Success Approve data');
        } catch (\Exception $e) {
            DB::rollback();
            log::debug($e->getMessage() . " on line " . $e->getLine() . " on file " . $e->getFile());
            return redirect()->back()->with('error', 'Job Has been created before.');
        }
    }

    public function getSearch(Request $request, $status, $created_by_customer, $startdate, $enddate)
    {
        $startdate = $startdate == 'all' ? $startdate : Carbon::parse($startdate)->format('Y-m-d');
        $enddate = $enddate == 'all' ? $enddate : Carbon::parse($enddate)->format('Y-m-d');
        $offset = $request->start;
        $per_page = $request->length;
        $limit = $per_page;
        $keyword = $request->search['value'];
        $order = $request->order[0];
        $sort = [];
        foreach ($request->order as $key => $o) {
            $columnIdx = $o['column'];
            $sortDir = $o['dir'];
            $sort[] = [
                'column' => $request->columns[$columnIdx]['name'],
                'dir' => $sortDir
            ];
        }
        $columns = $request->columns;
        $draw = $request->draw;
        $current_page = $offset / $limit + 1;
        $model = new Payment();
        $fields = $model->getTableColumns();
        $company_id = auth()->user()->company_id;
        $payment = Payment::select(['payments.*', 'payment_method.method', 'transactions.total_price', 'm_customer_vehicle.customer_name', 'm_package.package_name'])
            ->join('transactions', 'transactions.transaction_id', 'payments.transaction_id')
            ->join('m_package', 'm_package.package_id', 'transactions.package_id')
            ->join('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')
            ->join('payment_method', 'payment_method.payment_method_id', 'payments.payment_method_id')
            ->where('transactions.company_id', $company_id);

        if ($startdate != 'all' && $enddate != 'all')
            $payment = $payment->whereBetween(DB::raw("DATE_FORMAT(payments.payment_date, '%Y-%m-%d')"), [$startdate, $enddate]);

        if ($status != 'all')
            $payment = $payment->where('payments.approved', $status);
        if ($created_by_customer == '1')
            $payment = $payment->whereNotNull('payments.user_id');
        if ($created_by_customer == '0')
            $payment = $payment->whereNull('payments.user_id');
        // search data
        if ($keyword != null) {
            if (!empty($keyword)) {
                $payment->where(function ($query) use ($keyword, $fields) {
                    foreach ($fields as $column) {
                        $query->orWhere('payments.' . $column, 'LIKE', "%$keyword%");
                    }
                    $query->orWhere('transactions.total_price', 'LIKE', "%$keyword%");
                    $query->orWhere('m_package.package_name', 'LIKE', "%$keyword%");
                    $query->orWhere('m_customer_vehicle.customer_name', 'LIKE', "%$keyword%");
                    $query->orWhere('payment_method.method', 'LIKE', "%$keyword%");
                });
            }
        }
        $filteredData = $payment->get();

        // sort data
        if (!empty($sort)) {
            if (!is_array($sort)) {
                $message = "Invalid array for parameter sort";
                $data = [
                    'result' => FALSE,
                    'message' => $message
                ];
                $this->logActivity($request->path(), $message, print_r($_POST, TRUE));
                return response()->json($data);
            }

            foreach ($sort as $key => $s) {
                $column = $s['column'];
                $direction = $s['dir'];
                $payment->orderBy($column, $direction);
            }
        } else {
            $payment->orderBy('payments.transaction_id', 'asc');
            $payment->orderBy('payments.payment_date', 'desc');
        }
        $total_rows = count($payment->get());

        // paginate data
        if ($current_page != null) {
            $page = $current_page;
            $limit = count($payment->get());
            if ($per_page != null) {
                $limit = $per_page;
            }
            $offset = ($page - 1) * $limit;
            if ($offset < 0) {
                $offset = 0;
            }
            $payment->skip($offset)->take($limit);
        }
        $paymentList = $payment->get();

        $table['draw'] = $draw;
        $table['recordsTotal'] = $total_rows;
        $table['recordsFiltered'] = count($filteredData);
        $table['data'] = $paymentList;
        return json_encode($table);
    }

    public function getList(Request $request)
    {
        $payment = Payment::select(['transaction_id', DB::raw("DATE_FORMAT(payment_date, '%d-%m-%Y') as payment_date"), DB::raw("IF(approved = 0, 'Waiting', IF(approved = 1, 'Approved', 'Rejected')) as approved"), 'payment_id', 'total_payment'])
            ->where('transaction_id', $request->transaction_id)->where('user_id', auth()->user()->id)->get();

        return response()->json([
            'result' => true,
            'message' => 'Successfully get data',
            'data' => $payment
        ]);
    }

    public function getDetailTransaction(Request $request)
    {
        try {
            $transaction = Transaction::select(['transaction_id', DB::raw("DATE_FORMAT(order_date, '%d-%m-%Y') as order_date"), 'm_customer_vehicle.vehicle_name', 'm_package.package_name', 'total_price', 'transactions.company_id'])
                ->join('m_package', 'm_package.package_id', 'transactions.package_id')
                ->join('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')
                ->where('transaction_id', $request->transaction_id)->first();
            $historyPayment = Payment::where('transaction_id', $request->transaction_id)->sum('total_payment');

            $transaction->total_price = ($transaction->total_price - $historyPayment);
            $transaction->total_price = $transaction->total_price < 0 ? (string) 0 : (string) $transaction->total_price;

            $paymentMethod = PaymentMethod::where('company_id', $transaction->company_id)->where('void', 0)->get();
            foreach ($paymentMethod as $pm) {
                $pm['isExpanded'] = false;
            }

            $result = [
                'result' => true,
                'message' => 'Success get data',
                'data' => $transaction,
                'paymentMethod' => $paymentMethod
            ];
            return response()->json($result);
        } catch (\Exception $e) {
            log::debug($e->getMessage() . ' on line ' . $e->getLine() . ' on file ' . $e->getFile());
            $result = [
                'result' => false,
                'message' => 'Error getting the data result'
            ];
            return response()->json($result);
        }
    }

    public function savePaymentMobile(Request $request)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::find($request->transaction_id);
            $payment_id = $request->payment_id != '' ? $request->payment_id : null;
            if ($payment_id != null) {
                $payment = Payment::find($payment_id);
            } else {
                $payment = new Payment();
                $payment->payment_id = Str::orderedUuid();
                $payment->transaction_id = $request->transaction_id;
                $payment->payment_date = Carbon::now()->format('Y-m-d H:i:s');
                $payment->created_user = auth()->user()->username;
                $payment->approved = 0;
                $transaction->status = 1;
                $transaction->save();
            }
            $payment->payment_method_id = $request->payment_method_id;
            $payment->total_payment = $request->total_payment;
            $payment->user_id = auth()->user()->id;
            $payment->updated_user = auth()->user()->username;
            if ($request->file('file') != null) {
                // $picData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
                // file_put_contents($_SERVER['DOCUMENT_ROOT']."$servicePath"."$path", $picData);
                $file = $request->file('file');
                $name = "$payment->payment_id.png";
                $payment->file = "payment/$name";
                $path = $_SERVER['DOCUMENT_ROOT'] . "/vehiclecare/public/images/payment/";
                $file->move($path, $name);
            }
            $payment->save();

            $result = [
                'result' => true,
                'message' => 'Success saving your payment',
                'model' => $payment
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

    public function getDetailPayment(Request $request)
    {
        $payment = Payment::find($request->payment_id);

        $result = [
            'result' => true,
            'message' => 'Success get data',
            'data' => $payment
        ];
        return response()->json($result);
    }
}
