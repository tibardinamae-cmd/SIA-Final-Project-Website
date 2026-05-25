<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'total_amount',
        'payment',
        'change',
        'cashier_id',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
