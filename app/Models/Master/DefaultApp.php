<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class DefaultApp extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $incrementing = false;

    protected $table = 'm_default';
    protected $primaryKey = 'default_id';

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public static function getByID($id, $company_id)
    {
        $model = self::where('default_id', $id)->where('company_id', $company_id)->first()->value;

        return $model;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Master\Company' . 'company_id');
    }
}
