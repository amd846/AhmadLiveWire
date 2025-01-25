<?php

namespace App\Livewire;

use Livewire\Component;

class AcceptedTabButton extends Component
{

    public function render1()
    {
        return view('livewire.accepted-tab-button');
    }




     /*
    protected $listeners = ['funMoreTwo' => '$rejectedTab'];
    public function render()
    {
        return view('livewire.rejected-tab-button');
    }

    public function rejectedTab(){
        
    }

}






class ListOrders extends Component
{*/
protected $listeners = ['ListOrder' => '$refresh'];

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

private function calculatePercentageAcceptance($order)
{
    $ageFactor = ($order->user && $order->user->age < 30) ? 20 : 0;
    $moneyFactor = ($order->userRequiredAmount > 100) ? 30 : 0;
    $hoursFactor = ($order->user && $order->user->userLogIn_at > $order->user->userLogOut_at
        && Carbon::parse($order->user->userLogIn_at)->diffInHours(Carbon::now()) < 24) ? 20 : 0;

    return $ageFactor + $moneyFactor + $hoursFactor;
}

public function render()
{
    $activeSessions = $this->getActiveSessions();

    $orders = RejectedOrder::with('user:id,name,age,userLogIn_at,userLogOut_at')
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

  //      $this->dispatch('RejectedTab',['RejectedTab'>= $orders] );
   return view('livewire.accepted-tab', ['RejectedTab' => $orders]);

}
}


//}
