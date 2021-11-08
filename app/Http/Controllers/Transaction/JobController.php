<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Master\Company;
use App\Models\Master\Worker;
use App\Models\Master\Customer;
use App\Models\Master\Package;
use App\Models\Transaction\Job;
use App\Models\Transaction\Transaction;
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
        $company_id = auth()->user()->company_id;
        $vehicleType = Package::select('m_vehicle.*')->join('m_vehicle', 'm_vehicle.vehicle_id', 'm_package.vehicle_id')->where('m_package.company_id', $company_id)->where('m_package.active', 1)->groupBy('m_vehicle.vehicle_id')->get();

        return view('company.job.index')
            ->with('pageTitle', "Job List")
            ->with('vehicleType', $vehicleType);
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
        $model = new Job();
        $fields = $model->getTableColumns();
        $company_id = auth()->user()->company_id;
        $jobs = Job::select(['jobs.*', 'm_package.package_name', 'm_customer_vehicle.customer_name', 'm_customer_vehicle.vehicle_name', 'm_worker.worker_name'])
            ->join('transactions', 'transactions.transaction_id', 'jobs.transaction_id')
            ->join('m_package', 'm_package.package_id', 'transactions.package_id')
            ->join('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')
            ->leftjoin('m_worker', 'm_worker.worker_id', 'jobs.worker_id')
            ->where('m_package.company_id', $company_id);

        $jobs = $jobs->whereBetween(DB::raw("DATE_FORMAT(transactions.order_date, '%Y-%m-%d')"), [$startdate, $enddate]);
        if ($status != 'all') {
            if ($status != 'home') {
                $jobs = $jobs->where('jobs.status', $status);
            } else {
                $jobs = $jobs->where('jobs.status', '<>', 3);
            }
        }
        if ($vehicle_id != 'all')
            $jobs = $jobs->where('m_package.vehicle_id', $vehicle_id);

        // search data
        if ($keyword != null) {
            if (!empty($keyword)) {
                $jobs->where(function ($query) use ($keyword, $fields) {
                    foreach ($fields as $column) {
                        $query->orWhere('jobs.' . $column, 'LIKE', "%$keyword%");
                    }
                    $query->orWhere('m_customer_vehicle.customer_name', 'LIKE', "%$keyword%");
                    $query->orWhere('m_customer_vehicle.vehicle_name', 'LIKE', "%$keyword%");
                    $query->orWhere('m_worker.worker_name', 'LIKE', "%$keyword%");
                });
            }
        }
        $filteredData = $jobs->groupBy('jobs.transaction_id')->get();

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
                $jobs->orderBy($column, $direction);
            }
        } else {
            $jobs->orderBy('jobs.index', 'asc');
            $jobs->orderBy('jobs.status', 'desc');
        }
        $total_rows = count($jobs->groupBy('jobs.transaction_id')->get());

        // paginate data
        if ($current_page != null) {
            $page = $current_page;
            $limit = count($jobs->groupBy('jobs.transaction_id')->get());
            if ($per_page != null) {
                $limit = $per_page;
            }
            $offset = ($page - 1) * $limit;
            if ($offset < 0) {
                $offset = 0;
            }
            $jobs->skip($offset)->take($limit);
        }
        $jobsList = $jobs->groupBy('jobs.transaction_id')->get();

        $table['draw'] = $draw;
        $table['recordsTotal'] = $total_rows;
        $table['recordsFiltered'] = count($filteredData);
        $table['data'] = $jobsList;
        return json_encode($table);
    }

    public function search(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $model = new Job();
        $job = $model->searchJobWithVehicle($date, $request->company_id, $request->vehicle_id)->groupBy('jobs.transaction_id')->get();

        return response()->json([
            'result' => true,
            'message' => 'Success search job list',
            'data' => $job
        ]);
    }

    public function getJob(Request $request)
    {
        $worker = Worker::where('user_id', auth()->user()->id)->first();
        $model = Job::select(['jobs.transaction_id', 'm_customer_vehicle.customer_name', 'm_customer_vehicle.vehicle_name', DB::raw("IF(jobs.status = 0, 'Waiting', IF(jobs.status = 1, 'On Progress', IF(jobs.status = 2, 'Finished', 'Taken'))) as status")])
            ->join('transactions', 'transactions.transaction_id', 'jobs.transaction_id')
            ->join('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')
            ->where('worker_id', $worker->worker_id)->get();

        $result = [
            'result' => true,
            'data' => $model
        ];
        return response()->json($result);
    }

    public function checkJob(Request $request)
    {
        $worker = Worker::where('user_id', auth()->user()->id)->first();
        $transaction = Transaction::find($request->transaction_id)->where('company_id', $worker->company_id);
        if ($transaction == null) {
            $result = [
                'result' => false
            ];
        } else {
            $model = Job::where('transaction_id', $request->transaction_id)->first();
            if ($model != null) {
                $result = [
                    'result' => true
                ];
            } else {
                $result = [
                    'result' => false
                ];
            }
        }

        return response()->json($result);
    }

    public function jobDetail(Request $request)
    {
        $worker = Worker::where('user_id', auth()->user()->id)->first();
        $job = Job::select(['jobs.transaction_id', 'jobs.status', 'm_customer_vehicle.customer_name', 'm_customer_vehicle.vehicle_name', 'm_customer_vehicle.police_number', 'm_customer_vehicle.vehicle_photo_url', 'm_package.package_name', DB::raw("DATE_FORMAT(transactions.order_date, '%W, %d-%m-%Y') as order_date")])
            ->join('transactions', 'transactions.transaction_id', 'jobs.transaction_id')
            ->join('m_package', 'm_package.package_id', 'transactions.package_id')
            ->join('m_customer_vehicle', 'm_customer_vehicle.customer_vehicle_id', 'transactions.customer_vehicle_id')
            ->where('jobs.transaction_id', $request->transaction_id)->first();

        $result = [
            'result' => true,
            'message' => 'Success get data',
            'data' => $job
        ];
        return response()->json($result);
    }

    public function changeJobStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $worker = Worker::where('user_id', auth()->user()->id)->first();
            $transaction = Transaction::find($request->transaction_id);
            if ($transaction == null) {
                $result = [
                    'result' => false,
                    'message' => 'Data not found'
                ];
                DB::rollback();
                return response()->json($result);
            }
            if ($transaction->status != 2) {
                $result = [
                    'result' => false,
                    'message' => 'Transaction is not completed yet'
                ];
                DB::rollback();
                return response()->json($result);
            }
            $job = Job::where('transaction_id', $transaction->transaction_id)->orderBy('status', 'desc')->first();
            $newJob = Job::where('transaction_id', $transaction->transaction_id)->where('worker_id', $worker->worker_id)->first();
            if ($newJob == null) {
                if ($job->worker_id != null) {
                    $newJob = new Job();
                    $newJob->transaction_id = $transaction->transaction_id;
                    $newJob->worker_id = $worker->worker_id;
                    $newJob->index = $job->index;
                    $newJob->created_user = auth()->user()->username;
                    $newJob->start = $job->start;
                } else {
                    $newJob = $job;
                    $newJob->worker_id = $worker->worker_id;
                    $newJob->created_user = auth()->user()->username;
                    $newJob->start = $job->start;
                }
            }
            if ($job->status == 3) {
                $result = [
                    'result' => false,
                    'message' => 'Job has been finished and taken by the owner'
                ];
                DB::rollback();
                return response()->json($result);
            }
            if ($job->start == null) {
                $newJob->start = Carbon::now()->format('Y-m-d H:i:s');
            }
            if ($request->status == 2) {
                $newJob->end = Carbon::now()->format('Y-m-d H:i:s');
            }
            $newJob->status = $request->status;
            $job = Job::where('transaction_id', $transaction->transaction_id)->update(['status' => $request->status, 'end' => $newJob->end, 'updated_user' => auth()->user()->username]);
            $newJob->save();
            DB::commit();
            $result = [
                'result' => true,
                'message' => 'Success change job status'
            ];
            return response()->json($result);
        } catch (\Exception $e) {
            DB::rollback();
            log::debug($e->getMessage() . " on line " . $e->getLine() . ' on file ' . $e->getFile());
            $result = [
                'result' => false,
                'message' => 'Error while change job status'
            ];
            return response()->json($result);
        }
    }
}
