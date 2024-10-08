<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = ['transaction_id', 'transaction_category_id', 'name', 'value_idr'];

    public function transaction()
    {
        return $this->belongsTo(TransactionHeader::class);
    }

    public function category()
    {
        return $this->belongsTo(MsCategory::class);
    }
}
