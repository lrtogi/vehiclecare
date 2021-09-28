<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;
class Job extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $incrementing = false;

    protected $table = 'jobs';
    protected $primaryKey = 'transaction_id';

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function transactions(){
        return $this->belongsTo('App\Models\Transaction\Transaction', 'transaction_id', 'transaction_id');
    }
}
