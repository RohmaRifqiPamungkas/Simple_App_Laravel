<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHeader extends Model
{
    use HasFactory;
    
    protected $fillable = ['description', 'code', 'rate_euro', 'date_paid'];

    // Model TransactionHeader
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id'); // pastikan ini sesuai
    }

    // Model TransactionDetail
    public function header()
    {
        return $this->belongsTo(TransactionHeader::class, 'transaction_id'); // pastikan ini sesuai
    }
}
