<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Master\Company;
use App\Models\Master\Worker;
use App\Models\Master\Customer;
use App\Models\Master\Vehicle;
use App\Models\Master\CustomerVehicle;
use App\Models\Master\PaymentMethod;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\Job;
use App\Models\Transaction\Payment;
use App\Models\Master\Package;
use App\Models\Master\DefaultApp;
use App\Models\User;
use Log;
use DB;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TransactionController extends Controller
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
        $vehicleType = Vehicle::all();
        return view('company.transaction.index')
            ->with('pageTitle', "Transaction List")
            ->with('vehicleType', $vehicleType);
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $company_id = auth()->user()->company_id;
            $transaction = Transaction::leftjoin('jobs', 'jobs.transaction_id', 'transactions.transaction_id')->join('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')->where('transactions.transaction_id', $request->transaction_id)->select(['transactions.company_id', 'transactions.status as transaction_status', 'jobs.status as job_status', 'transactions.customer_vehicle_id', 'm_customer_vehicle.customer_id'])->first();

            if ($transaction == null || $transaction->company_id != $company_id || $transaction->job_status != 0 || $transaction->transaction_status == 1 || $transaction->transaction_status == 0 || $transaction->customer_id != null) {
                return redirect()->back()->with('error', 'You cannot delete this data.');
            }
            $jobs = Job::where('transaction_id', $request->transaction_id)->delete();
            $payment = Payment::where('transaction_id', $request->transaction_id)->delete();
            $temp = CustomerVehicle::findOrfail($transaction->customer_vehicle_id);
            $transaction = Transaction::where('transaction_id', $request->transaction_id)->delete();
            $customerVehicle = CustomerVehicle::where('customer_vehicle_id', $temp->customer_vehicle_id)->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Success deleting data.');
        } catch (\Exception $e) {
            DB::rollback();
            log::debug($e->getMessage() . " on line " . $e->getLine() . " on file " . $e->getFile());
            return redirect()->back()->with('error', 'Error while deleting data.');
        }
    }

    public function showForm(Request $request, $transaction_id = null)
    {
        if ($transaction_id != null) {
            $model = Transaction::leftjoin('jobs', 'jobs.transaction_id', 'transactions.transaction_id')
                ->join('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')
                ->where('transactions.transaction_id', $transaction_id)
                ->where('jobs.status', 0)->first();
            if ($model == null) {
                return redirect()->back()->with('error', 'You cannot edit data that has been processed.');
            }
            if ($model->company_id != auth()->user()->company_id) {
                return redirect()->back()->with('error', 'You are not allowed to edit this data.');
            }
            if ($model->customer_id != null) {
                return redirect()->back()->with('error', 'This transaction was created by another user');
            }
        } else {
            $model = new Transaction();
        }
        $vehicleType = Vehicle::all();
        $package = Package::where('company_id', auth()->user()->company_id)->get();
        $paymentMethod = PaymentMethod::where('company_id', auth()->user()->company_id)->get();

        return view('company.transaction.form')
            ->with('pageTitle', 'Transaction Form')
            ->with('model', $model)
            ->with('vehicleType', $vehicleType)
            ->with('package', $package);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $company_id = auth()->user()->company_id;
        $defaultPaymentMethod = DefaultApp::getByID('Default Payment Method', $company_id);
        $transaction_id = $request->input('transactionID') != null ? $request->transactionID : null;
        $package = Package::where('package_id', $request->package_type)->where('company_id', $company_id)->first();
        if ($package == null) {
            return redirect()->back()->with('error', 'Package Type not found!')->withInput();
        }
        if ($transaction_id == null) {
            $customerVehicle = new CustomerVehicle();
            $customerVehicle->customer_vehicle_id = Str::orderedUuid();
            $customerVehicle->customer_name = $request->customer_name;
            $customerVehicle->vehicle_id = $request->vehicle_type;
            $customerVehicle->vehicle_name = $request->vehicle_name;
            $customerVehicle->police_number = $request->police_number;
            $customerVehicle->created_user = auth()->user()->username;
            $customerVehicle->updated_user = auth()->user()->username;
            $customerVehicle->save();

            $transaction = new Transaction();
            $transaction->transaction_id = Str::orderedUuid();
            $transaction->transaction_date = Carbon::now()->format('Y-m-d H:i:s');
            $transaction->order_date = Carbon::parse($request->order_date)->format('Y-m-d');
            $transaction->customer_vehicle_id = $customerVehicle->customer_vehicle_id;
            $transaction->company_id = $company_id;
            $transaction->package_id = $package->package_id;
            $transaction->qty = 1;
            $transaction->total_price = $transaction->qty * $package->discounted_price;
            $transaction->status = 2;
            $transaction->created_user = auth()->user()->username;
            $transaction->updated_user = auth()->user()->username;
            $transaction->save();
            // $qrcode = QrCode::size(300)->format('png')->generate('I Love You Elita', public_path('images/'.$transaction->transaction_id.'.png'));

            $payment = new Payment();
            $payment->payment_id = Str::orderedUuid();
            $payment->payment_date = Carbon::now()->format('Y-m-d H:i:s');
            $payment->transaction_id = $transaction->transaction_id;
            $payment->total_payment = $transaction->total_price;
            $payment->payment_method_id = $defaultPaymentMethod;
            $payment->approved = 1;
            $payment->created_user = auth()->user()->username;
            $payment->updated_user = auth()->user()->username;
            $payment->save();

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
            $job->save();
        } else {
            $transaction = Transaction::findOrFail($transaction_id);
            $customerVehicle = CustomerVehicle::findOrFail($transaction->customer_vehicle_id);
            $job = Job::where('transaction_id', $transaction->transaction_id)->first();
            $payment = Payment::where('transaction_id', $transaction->transaction_id)->first();
            if ($transaction->company_id != auth()->user()->company_id || $job->status != 0) {
                return redirect()->back()->with('error', 'You are not allowed to edit this data.');
            }
            $customerVehicle->customer_name = $request->customer_name;
            $customerVehicle->vehicle_id = $request->vehicle_type;
            $customerVehicle->vehicle_name = $request->vehicle_name;
            $customerVehicle->police_number = $request->police_number;
            $customerVehicle->updated_user = auth()->user()->username;
            $customerVehicle->save();

            $transaction->order_date = Carbon::parse($request->order_date)->format('Y-m-d');
            $transaction->customer_vehicle_id = $customerVehicle->customer_vehicle_id;
            $transaction->package_id = $package->package_id;
            $transaction->qty = 1;
            $transaction->total_price = $transaction->qty * $package->discounted_price;
            $transaction->updated_user = auth()->user()->username;
            $transaction->save();

            $payment->payment_date = Carbon::now()->format('Y-m-d H:i:s');
            $payment->payment_method_id = $defaultPaymentMethod;
            $payment->total_payment = $transaction->total_price;
            $payment->updated_user = auth()->user()->username;
            $payment->save();
        }

        DB::commit();
        $success = "Success saving transaction.";
        return redirect()->route('transaction/detail', $transaction->transaction_id)->with('success', $success);
    }

    public function detail($id)
    {
        $company_id = auth()->user()->company_id;
        $model = Transaction::join('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')->join('m_package', 'm_package.package_id', 'transactions.package_id')->leftjoin('jobs', 'jobs.transaction_id', 'transactions.transaction_id')->where('transactions.transaction_id', $id)->select(['transactions.order_date', 'transactions.total_price', DB::raw("IF(transactions.status=0,'Pending Payment',IF(transactions.status=1,'Pending Approval',IF(transactions.status=2,'Approved',IF(transactions.status=3,'Declined','Error')))) as status"), 'm_customer_vehicle.customer_name', 'm_customer_vehicle.police_number', 'm_customer_vehicle.vehicle_name', 'm_package.package_name', 'jobs.index', 'transactions.company_id', 'transactions.transaction_id'])->first();

        if ($model->company_id != $company_id || empty($model)) {
            return redirect()->back()->with('error', 'Unable to retrieve data');
        }

        return view('company.transaction.detail')
            ->with('pageTitle', 'Transaction Detail')
            ->with('model', $model);
    }

    public function print($id)
    {
        $company_id = auth()->user()->company_id;
        $model = Transaction::join('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')->join('m_package', 'm_package.package_id', 'transactions.package_id')->leftjoin('jobs', 'jobs.transaction_id', 'transactions.transaction_id')->where('transactions.transaction_id', $id)->select(['transactions.order_date', 'transactions.total_price', DB::raw("IF(transactions.status=0,'Pending Payment',IF(transactions.status=1,'Pending Approval',IF(transactions.status=2,'Approved',IF(transactions.status=3,'Declined','Error')))) as status"), 'm_customer_vehicle.customer_name', 'm_customer_vehicle.police_number', 'm_customer_vehicle.vehicle_name', 'm_package.package_name', 'jobs.index', 'transactions.company_id', 'transactions.transaction_id'])->first();

        if ($model->company_id != $company_id || empty($model)) {
            return redirect()->back()->with('error', 'Unable to retrieve data');
        }

        $data = [
            'header' => "Print Transaction",
            'model' => $model
        ];

        $pdf = PDF::loadView('print-transaction', $data);
        $pdf->setOption('page-width', '90.9');
        $pdf->setOption('page-height', '60.7');
        $pdf->setOption('zoom', '1.45');
        $pdf->setOrientation('landscape');
        $pdf->setOption('margin-right', '3');
        $pdf->setOption('margin-left', '3');
        $pdf->setOption('margin-top', '2');
        // dd($pdf);
        return $pdf->stream('print-transaction');
    }

    public function getSearch(Request $request, $status, $vehicle_id, $startdate, $enddate)
    {
        $startdate = Carbon::parse($startdate)->format('Y-m-d');
        $enddate = Carbon::parse($enddate)->format('Y-m-d');
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
        $model = new Transaction();
        $modelVehicle = new Vehicle();
        $fields = $model->getTableColumns();
        $fieldsVehicle = $modelVehicle->getTableColumns();
        $company_id = auth()->user()->company_id;
        $transaction = Transaction::leftjoin('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')->leftjoin('m_vehicle', 'm_vehicle.vehicle_id', 'm_customer_vehicle.vehicle_id')->join('m_package', 'm_package.package_id', 'transactions.package_id')->where('m_package.company_id', $company_id)->where('transactions.company_id', $company_id)->select(['transactions.*', 'm_vehicle.vehicle_type', 'm_customer_vehicle.customer_name', 'm_package.package_name', 'm_customer_vehicle.customer_id']);
        $transaction = $transaction->whereBetween('transactions.order_date', [$startdate, $enddate]);
        if ($status != 'all')
            $transaction = $transaction->where('transactions.status', $status);
        if ($vehicle_id != 'all')
            $transaction = $transaction->where('m_vehicle.vehicle_id', $vehicle_id);

        // search data
        if ($keyword != null) {
            if (!empty($keyword)) {
                $transaction->where(function ($query) use ($keyword, $fields, $fieldsVehicle) {
                    foreach ($fields as $column) {
                        $query->orWhere('transactions.' . $column, 'LIKE', "%$keyword%");
                    }
                    foreach ($fieldsVehicle as $column) {
                        $query->orWhere('m_vehicle.' . $column, 'LIKE', "%$keyword%");
                    }
                    $query->orWhere('m_package.package_name', 'LIKE', "%$keyword%");
                    $query->orWhere('m_customer_vehicle.customer_name', 'LIKE', "%$keyword%");
                });
            }
        }
        $filteredData = $transaction->get();

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
                $transaction->orderBy($column, $direction);
            }
        } else {
            $transaction->orderBy('transactions.transaction_date', 'asc');
        }
        $total_rows = count($transaction->get());

        // paginate data
        if ($current_page != null) {
            $page = $current_page;
            $limit = count($transaction->get());
            if ($per_page != null) {
                $limit = $per_page;
            }
            $offset = ($page - 1) * $limit;
            if ($offset < 0) {
                $offset = 0;
            }
            $transaction->skip($offset)->take($limit);
        }
        $transactionList = $transaction->get();

        $table['draw'] = $draw;
        $table['recordsTotal'] = $total_rows;
        $table['recordsFiltered'] = count($filteredData);
        $table['data'] = $transactionList;
        return json_encode($table);
    }

    public function packageSearchMobile(Request $request)
    {
        try {
            $vehicle = CustomerVehicle::find($request->customer_vehicle_id);
            $package = Package::where('company_id', $request->company_id)->where('vehicle_id', $vehicle->vehicle_id)->where('active', 1)->get();

            $result = [
                'result' => true,
                'message' => 'Success get package data',
                'data' => $package
            ];
            return response()->json($result);
        } catch (\Exception $e) {
            $message = "Failed to retrieve data";
            $result = [
                'result' => false,
                'message' => $message
            ];
            return response()->json($result);
        }
    }

    public function getDetailPackage(Request $request)
    {
        try {
            $package = Package::find($request->package_id);

            $result = [
                'result' => true,
                'message' => 'Success get data',
                'data' => $package
            ];
            return response()->json($result);
        } catch (\Exception $e) {
            log::debug($e->getMessage());
            $message = "Failed to retrieve data";
            $result = [
                'result' => false,
                'message' => $message
            ];
            return response()->json($result);
        }
    }

    public function getListData(Request $request)
    {
        try {
            $transaction = Transaction::join('m_package', 'm_package.package_id', 'transactions.package_id')->join('m_company', 'm_company.company_id', 'm_package.company_id')->join('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')->where('m_customer_vehicle.customer_id', $request->customer_id)->select(['transactions.transaction_id', 'm_customer_vehicle.vehicle_name', 'total_price', 'package_name', DB::raw("DATE_FORMAT(order_date, '%Y-%m-%d') as order_date"), 'transactions.company_id', 'm_company.company_name', 'transactions.status'])->get();

            $result = [
                'result' => true,
                'message' => 'Success get Transaction List',
                'data' => $transaction
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

    public function getData(Request $request)
    {
        try {
            $transaction = Transaction::leftjoin('jobs', 'jobs.transaction_id', 'transactions.transaction_id')
                ->where('transactions.transaction_id', $request->transaction_id)
                ->select(['transactions.transaction_id', DB::raw("DATE_FORMAT(transactions.order_date, '%d-%m-%Y') as order_date"), 'customer_vehicle_id', 'package_id', 'total_price', 'company_id', 'transactions.status as status', 'jobs.status as job_status'])->first();
            $job = Job::find($transaction->transaction_id);
            if (!empty($job) && $transaction->status != 0 && $transaction->status != 1 && $transaction->status != 4)
                $editable = false;
            else
                $editable = true;

            if ($transaction->status == 2) {
                $showQR = true;
            } else {
                $showQR = false;
            }

            $result = [
                'result' => true,
                'message' => 'Success get data',
                'data' => $transaction,
                'editable' => $editable,
                'showQR' => $showQR
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

    public function saveMobileForm(Request $request)
    {
        DB::beginTransaction();
        try {
            $company_id = $request->company_id;
            $transaction_id = $request->input('transaction_id') != '' ? $request->transaction_id : null;
            $package = Package::where('package_id', $request->package_id)->where('company_id', $company_id)->first();
            $customerVehicle = CustomerVehicle::find($request->customer_vehicle_id);
            if ($package == null) {
                $result = [
                    'result' => false,
                    'message' => 'Package Not Found'
                ];
                return response()->json($result);
            }
            if ($transaction_id == null) {
                $transaction = new Transaction();
                $transaction->transaction_id = Str::orderedUuid();
                $transaction->transaction_date = Carbon::now()->format('Y-m-d H:i:s');
                $transaction->order_date = Carbon::parse($request->order_date)->format('Y-m-d');
                $transaction->customer_vehicle_id = $customerVehicle->customer_vehicle_id;
                $transaction->company_id = $company_id;
                $transaction->package_id = $package->package_id;
                $transaction->qty = 1;
                $transaction->total_price = $transaction->qty * $package->discounted_price;
                $transaction->status = 0;
                $transaction->created_user = auth()->user()->username;
                $transaction->updated_user = auth()->user()->username;
                $transaction->save();
            } else {
                $transaction = Transaction::findOrFail($transaction_id);
                $job = Job::where('transaction_id', $transaction->transaction_id)->first();
                $payment = Payment::where('transaction_id', $transaction->transaction_id)->first();
                if ($transaction->company_id != $request->company_id || !empty($job)) {
                    $result = [
                        'result' => false,
                        'message' => "You are cannot edit this data"
                    ];
                    return response()->json($result);
                }

                $transaction->order_date = Carbon::parse($request->order_date)->format('Y-m-d');
                $transaction->customer_vehicle_id = $customerVehicle->customer_vehicle_id;
                $transaction->package_id = $package->package_id;
                $transaction->total_price = $transaction->qty * $package->discounted_price;
                $transaction->updated_user = auth()->user()->username;
                $transaction->save();
            }

            DB::commit();
            $success = "Success saving transaction.";
            $result = [
                'result' => true,
                'message' => $success
            ];
            return response()->json($result);
        } catch (\Exception $e) {
            DB::rollback();
            log::debug($e->getMessage() . ' on line ' . $e->getLine() . ' on file ' . $e->getFile());
            $result = [
                'result' => false,
                'message' => 'An error occured while saving transaction'
            ];
            return response()->json($result);
        }
    }

    public function deleteMobileTransaction(Request $request)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::leftjoin('jobs', 'jobs.transaction_id', 'transactions.transaction_id')->where('transactions.transaction_id', $request->transaction_id)->select(['transactions.company_id', 'transactions.status as transaction_status', 'jobs.status as job_status', 'transactions.customer_vehicle_id'])->first();

            if ($transaction == null || $transaction->transaction_status != 0) {
                $result = [
                    'result' => true,
                    'message' => 'You cannot delete this data'
                ];
                return response()->json($result);
            }
            $transaction = Transaction::where('transaction_id', $request->transaction_id)->delete();

            DB::commit();
            $result = [
                'result' => true,
                'message' => 'Successfully Deleted Data'
            ];
            return response()->json($result);
        } catch (\Exception $e) {
            DB::rollback();
            log::debug($e->getMessage() . " on line " . $e->getLine() . " on file " . $e->getFile());
            $result = [
                'result' => false,
                'message' => 'An Error occured while deleting data'
            ];
            return response()->json($result);
        }
    }
}
