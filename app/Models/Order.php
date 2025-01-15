<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userOrderNumber',
        'userID',
        'userRequiredAmount',
        'userPercentageAcceptance',
        'userOrderStatus',
        'userOrderDate',
        'userTransferOrder',
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
