<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Admin_Fixed;
use App\Models\Order;
use App\Models\AcceptedOrder;


class AcceptِAdmin extends Component
{
    protected $listeners = ['funAcceptAdmin' => 'acceptAdmin'];
    public function render()
    {
        return view('livewire.acceptِ-admin');
    }
    public function acceptAdmin(){
        $fixedMoney = Admin_Fixed::first()->fixedMoney;

    $cause= 'مبلغ من الادمن';
    $acceptedOrders = Order::where('userRequiredAmount', '>', $fixedMoney)
    ->where('userTransferOrder', false)
    ->get();

  //  $this->addAcceptedOrders( $acceptedOrders,$cause);
 
    // Update orders where the userRequiredAmount is greater than the fixedMoney
     Order::where('userRequiredAmount', '>', $fixedMoney)
        ->update([
            'userOrderStatus' => 'مقبول',
                'userTransferOrder' => true,
            'cause' => 'مبلغ من الادمن'
        ]);
        
           foreach ($acceptedOrders as $order) {
            AcceptedOrder::create([
                'userOrderNumber' => $order->id,
                'userID' => $order->userID,
                'userRequiredAmount' => $order->userRequiredAmount,
                'userPercentageAcceptance' => $order->userPercentageAcceptance,
                'userOrderStatus' => 'مقبول',
                'userOrderDate' => $order->userOrderDate,
                'cause' => $cause,
            ]);
        }
        
      
        $this->dispatch('ListOrder');
    }
}
