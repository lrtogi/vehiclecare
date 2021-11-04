<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;
class PaymentMethod extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $incrementing = false;

    protected $table = 'payment_method';
    protected $primaryKey = 'payment_method_id';

    protected $guarded = ['payment_method_id', 'method', 'value', 'company_id', 'on_behalf_of', 'void'];

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
