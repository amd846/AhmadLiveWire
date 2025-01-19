<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\RejectedOrder;
use App\Models\User;
use App\Services\orderService;


 
use Illuminate\Support\Facades\DB;
 
 
 
use Carbon\Carbon;

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





       
       $activeSessions = $this->getActiveSessions();

       $orders = Order::with('user:id,name,age,userLogIn_at,userLogOut_at')
           ->select([
               'id',
               'userID',
               'userRequiredAmount',
               'userOrderDate',
               'userPercentageAcceptance',
               'userOrderStatus',
               'cause',
           ])
           ->where('userTransferOrder', false)
           ->get()
           ->map(function ($order) use ($activeSessions) {
               return [
                   'UserName' => $order->user->name ?? __('Unavailable'),
                   'RequiredAmount' => $order->userRequiredAmount,
                   'OrderID' => $order->id,
                   'OrderDate' => $order->userOrderDate->format('Y-m-d H:i:s'),
                   'PercentageAcceptance' => $this->calculatePercentageAcceptance($order),
                   'OrderStatus' => $order->userOrderStatus,
                   'Reason' => $order->cause ?? ' ',
                   'Age' => $order->user->age,
                   'OnOff' => in_array($order->userID, $activeSessions->toArray()) 
                       ? __('Active') 
                       : __('Inactive'),
                   'UserID' => $order->userID,
               ];
           });




      // $orderService = new orderService();
      //  dd ($orderService->rejectedOrders());
       // $this->dispatch('ٌRejectedTab');
       // $this->dispatch('ListOrder',['RejectedTab', 'ListOrder']);
     $this->dispatch('ListOrders',['orders'>= $orders] );
    

       //$this->dispatch('refreshComponents',['RejectedTab'=> $RejectedTab, 'ListOrders'=>$orders],
      // $this->dispatch('refreshComponents',[ 'ListOrders'=>$orders],
       // );
       
    }


    private function getActiveSessions()
    {
      /*  return User::whereNotNull('id')
            ->where('userLogIn_at', '>=', now()->subDays(config('session.lifetime'))->timestamp)
            ->pluck('id');
*/
            return   DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
            ->pluck('user_id');
    }
}
