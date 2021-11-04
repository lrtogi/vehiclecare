<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Job extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $incrementing = false;

    protected $table = 'jobs';
    protected $primaryKey = 'transaction_id';

    public function searchJobWithVehicle($date, $company_id, $vehicle_id)
    {
        $job = Job::select(['m_customer_vehicle.customer_name', 'jobs.index', 'm_package.package_name', DB::raw("IF(jobs.status = 0, 'Waiting', IF(jobs.status = 1, 'On process', IF(jobs.status = 2, 'Finished', 'Done'))) as status")])
            ->join('transactions', 'transactions.transaction_id', 'jobs.transaction_id')
            ->join('m_customer_vehicle', 'transactions.customer_vehicle_id', 'm_customer_vehicle.customer_vehicle_id')
            ->join('m_package', 'm_package.package_id', 'transactions.package_id')
            ->where('transactions.company_id', $company_id)
            ->where('transactions.order_date', $date)
            ->where('m_customer_vehicle.vehicle_id', $vehicle_id)
            ->where('jobs.status', '<>', 2);
        return $job;
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function transactions()
    {
        return $this->belongsTo('App\Models\Transaction\Transaction', 'transaction_id', 'transaction_id');
    }
}
