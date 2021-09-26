<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Master\Company;
use App\Models\Master\Worker;
use App\Models\Master\Customer;
use App\Models\Master\Vehicle;
use App\Models\Transaction\Transaction;
use App\Models\User;
use Log;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function rejectPayment(Request $request){

    }

    public function approvePayment(Request $request){

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
