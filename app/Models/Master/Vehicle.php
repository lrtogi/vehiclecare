<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;
class Vehicle extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $incrementing = false;

    protected $table = 'm_vehicle';
    protected $primaryKey = 'vehicle_id';
    protected $fillable = [
        'vehicle_id', 'vehicle_type', 'created_user', 'updated_user'
    ];
    protected $guarded = ['vehicle_id', '_token'];

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'user_record');
    }
}
