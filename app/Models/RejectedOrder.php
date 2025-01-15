<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RejectedOrder extends Model
{
    //
    protected $fillable = [
        'userOrderNumber',
        'userID',
        'userRequiredAmount',
        'userPercentageAcceptance',
        'userOrderStatus',
        'userOrderDate',
        'userTransferOrder',
        'cause'
    ];


    protected $casts = [
        'userRequiredAmount' => 'decimal:2',
        'userPercentageAcceptance' => 'decimal:2',
        'userOrderDate' => 'datetime',
        'userTransferOrder' => 'boolean',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }
}
