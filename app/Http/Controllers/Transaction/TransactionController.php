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
use App\Models\Transaction\Transaction;
use App\Models\Transaction\Job;
use App\Models\Transaction\Payment;
use App\Models\Master\Package;
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
        $user = User::all();
        $vehicleType = Vehicle::all();
        return view('company.transaction.index')
        ->with('pageTitle', "Transaction List")
        ->with('vehicleType', $vehicleType);
    }

    public function showForm(Request $request, $transaction_id = null){
        if($transaction_id != null){
            $model = Transaction::join('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')->where('transaction_id',$transaction_id)->where('status', 0)->first();
            if($model == null){
                return redirect()->back()->with('error', 'You cannot edit data that has been processed.');
            }
            if($model->company_id != auth()->user()->company_id){
                return redirect()->back()->with('error', 'You are not allowed to edit this data.');
            }
            if($model->customer_id == null){
                return redirect()->back()->with('error', 'This transaction was created by another user');
            }
        }
        else{
            $model = new Transaction();
        }
        $vehicleType = Vehicle::all();
        $package = Package::where('company_id', auth()->user()->company_id);

        return view('company.transaction.form')
        ->with('pageTitle', 'Transaction Form')
        ->with('model', $model)
        ->with('vehicleType', $vehicleType)
        ->with('package', $package);
    }

    public function store(Request $request){
        DB::beginTransaction();
        $company_id = auth()->user()->company_id;
        $transaction_id = $request->input('transactionID') != null ? $request->transactionID : null;
        $package = Package::where('package_id', $request->package_type)->where('company_id', $company_id)->first();
        if($package == null){
            return redirect()->back()->with('error', 'Package Type not found!')->withInput();
        }
        if($transaction_id == null){
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
            $transaction->transaction_date = Carbon::now()->format('Y-m-d');
            $transaction->order_date = Carbon::parse($request->order_date)->format('Y-m-d');
            $transaction->customer_vehicle_id = $customerVehicle->customer_vehicle_id;
            $transaction->company_id = $company_id;
            $transaction->package_id = $package->package_id;
            $transaction->total_price = $package->discounted_price;
            $transaction->status = 2;
            $transaction->created_user = auth()->user()->username;
            $transaction->updated_user = auth()->user()->username;
            $transaction->save();
            // $qrcode = QrCode::size(300)->format('png')->generate('I Love You Elita', public_path('images/'.$transaction->transaction_id.'.png'));

            $payment = new Payment();
            $payment->payment_id = Str::orderedUuid();
            $payment->payment_date = Carbon::now()->format('Y-m-d');
            $payment->transaction_id = $transaction->transaction_id;
            $payment->total_payment = $transaction->total_price;
            $payment->approved = 1;
            $payment->created_user = auth()->user()->username;
            $payment->updated_user = auth()->user()->username;
            $payment->save();

            $job = new Job();
            $job->transaction_id = $transaction->transaction_id;
            $countIndex = Job::join('transactions', 'transactions.transaction_id', 'jobs.transaction_id')->where('transactions.order_date', $transaction->order_date)->count();
            $job->index = $countIndex + 1;
            $job->status = 0;
            $job->created_user = auth()->user()->username;
            $job->updated_user = auth()->user()->username;
            $job->save();
        }
        else{

        }

        $data = [
                'header' => "Transaction Detail",
                'transaction' => $transaction,
                'customerVehicle' => $customerVehicle
            ];

            $pdf = PDF::loadView('print-transaction', $data);
            $pdf->setPaper('A4');
            $pdf->setOrientation('landscape');
            $pdf->setOption('margin-right', '3');
            $pdf->setOption('margin-left', '3');
            $pdf->setOption('margin-top', '2');
            // dd($pdf);
            DB::commit();
            return $pdf->stream('print-transaction');
    }

    public function getSearch(Request $request, $status, $vehicle_id, $startdate, $enddate){
        log::debug('tesst');
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
            $transaction = Transaction::leftjoin('m_customer_vehicle','m_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')->leftjoin('m_vehicle','m_vehicle.vehicle_id', 'm_customer_vehicle.vehicle_id')->join('m_package', 'm_package.package_id', 'transactions.package_id')->where('m_package.company_id', $company_id)->where('transactions.company_id', $company_id)->select(['transactions.*', 'm_vehicle.vehicle_type', 'm_customer_vehicle.customer_name', 'm_package.package_name']);
            if($status != 'all')
                $transaction = $transaction->where('transactions.status', $status);
            if($vehicle_id != 'all')
                $transaction = $transaction->where('m_vehicle.vehicle_id', $vehicle_id);

            // search data
            if ($keyword != null) {
                if (!empty($keyword)) {
                    $transaction->where(function ($query) use ($keyword, $fields, $fieldsVehicle) {
                        foreach ($fields as $column) {
                            $query->orWhere('transactions.'.$column, 'LIKE', "%$keyword%");
                        }
                        foreach ($fieldsVehicle as $column) {
                            $query->orWhere('m_vehicle.'.$column, 'LIKE', "%$keyword%");
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
            // $table['post_data'] = $post_data;

            return json_encode($table);
        
    }

}
