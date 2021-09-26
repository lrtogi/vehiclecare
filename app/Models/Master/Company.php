<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;
class Company extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $incrementing = false;

    protected $table = 'm_company';
    protected $primaryKey = 'company_id';
    protected $fillable = [
        'company_name', 'company_id', 'pic_email', 'no_telp', 'agama', 'alamat_perusahaan', 'created_user', 'updated_user', 'active', 'approved'
    ];
    protected $guarded = ['company_id', '_token'];

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
