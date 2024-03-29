<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;
class Package extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $incrementing = false;

    protected $table = 'm_package';
    protected $primaryKey = 'package_id';

    protected $guarded = ['package_id', 'package_name', 'company_id', 'vehicle_id', 'price', 'discounted_percentage', 'discounted_price', 'active'];

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function user() {
        return $this->belongsTo('App\User', 'username', 'user_record');
    }

    public function vehicle() {
        return $this->belongsTo('App\Models\Master\Vehicle', 'vehicle_id');
    }
}
