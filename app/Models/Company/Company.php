<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;
class Company extends Model
{
    const CREATED_AT = 'dt_record';
    const UPDATED_AT = 'dt_modified';

    public $incrementing = false;

    const UNIQUE_PREFIX_CHAR = 'S';
    const TOTAL_NUMBER_DIGIT_CHAR = 6;

    protected $table = 'm_company';
    protected $primaryKey = 'company_id';

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'user_record');
    }
}
