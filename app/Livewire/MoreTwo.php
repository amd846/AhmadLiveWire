<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\RejectedOrder;
use App\Models\User;

class MoreTwo extends Component
{
    protected $listeners = ['funMoreTwo' => 'moreTwo'];
    public function render()
    {
        return view('livewire.more-two');
    }
    public function moreTwo(){
        $inactiveUsers = User::where('userLogIn_at', '<', now()->subDays(2))
        ->pluck('id');
     
      
     
        $rejectedOrders = Order::whereIn('userID', $inactiveUsers)
        ->where('userTransferOrder', false)
        ->get();
       
     
         $cause = 'لم يتم الدخول لاكثر من يومين';
    
      // Update orders for these users
        if ($rejectedOrders->isNotEmpty()) {
        Order::whereIn('userID',  $inactiveUsers )
            ->update([
                'userOrderStatus' => 'مرفوض',
                'userTransferOrder' => true,
                'cause' => $cause,
            ]);
    
        // Call the addRejectedOrders method
        
        foreach ($rejectedOrders as $order) {
            RejectedOrder::create([
                'userOrderNumber' => $order->id,
                'userID' => $order->userID,
                'userRequiredAmount' => $order->userRequiredAmount,
                'userPercentageAcceptance' => $order->userPercentageAcceptance,
                'userOrderStatus' => 'مرفوض',
                'userOrderDate' => $order->userOrderDate,
                'cause' => $cause
               // 'userTransferOrder' => $order->userTransferOrder,
            ]);
        }  

       }
       $this->dispatch('ListOrder');
    }
}
