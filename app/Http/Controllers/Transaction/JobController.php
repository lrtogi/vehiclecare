<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Master\Company;
use App\Models\Master\Worker;
use App\Models\Master\Customer;
use App\Models\Transaction\Job;
use App\Models\User;
use Log;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
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
        return view('company.job.index')
        ->with('pageTitle', "Job List");
    }

    public function rejectPayment(Request $request){

    }

    public function approvePayment(Request $request){

    }

    public function getSearch(Request $request, $status, $created_by_customer, $startdate, $enddate){
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
        $model = new Payment();
        $fields = $model->getTableColumns();
        $company_id = auth()->user()->company_id;
        $payment = Payment::join('transactions', 'transactions.transaction_id', 'payments.transaction_id')->join('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')->where('transactions.company_id', $company_id)->select(['payments.*', 'transactions.total_price', 'm_customer_vehicle.customer_name']);
        
        $payment = $payment->whereBetween(DB::raw("DATE_FORMAT(payments.payment_date, '%Y-%m-%d')"), [$startdate, $enddate]);
        if($status != 'all')
            $payment = $payment->where('payments.approved', $status);
        if($created_by_customer == '1')
            $payment = $payment->whereNotNull('payments.user_id');
        if($created_by_customer == '0')
            $payment = $payment->whereNull('payments.user_id');
        // search data
        if ($keyword != null) {
            if (!empty($keyword)) {
                $payment->where(function ($query) use ($keyword, $fields) {
                    foreach ($fields as $column) {
                        $query->orWhere('payments.'.$column, 'LIKE', "%$keyword%");
                    }
                    $query->orWhere('transactions.total_price', 'LIKE', "%$keyword%");
                    $query->orWhere('m_customer_vehicle.customer_name', 'LIKE', "%$keyword%");
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

    public function search(Request $request){
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $model = new Job();
        $job = $model->searchJobWithVehicle($date, $request->company_id, $request->vehicle_id)->get();

        return response()->json([
            'result' => true,
            'message' => 'Success search job list',
            'data' => $job
        ]);
    }

}
