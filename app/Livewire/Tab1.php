<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Log;
use Livewire\Component;


use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\RejectedOrder;
use App\Models\User;
use Carbon\Carbon;


class Tab1 extends Component
{
     
    protected $listeners = ['funTab1' => 'funTab1'];
/*
    public function render()
    {
        Log::info('This is an info log for Tab 1.');
        return view('livewire.tab1',['RejectedTab'=>'']);
    }


    */
    public function funTab1(){
        Log::info('This is an info log for FunTab 1.');  
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

    private function calculatePercentageAcceptance($order)
    {
        $ageFactor = ($order->user && $order->user->age < 30) ? 20 : 0;
        $moneyFactor = ($order->userRequiredAmount > 100) ? 30 : 0;
        $hoursFactor = ($order->user && $order->user->userLogIn_at > $order->user->userLogOut_at
            && Carbon::parse($order->user->userLogIn_at)->diffInHours(Carbon::now()) < 24) ? 20 : 0;

        return $ageFactor + $moneyFactor + $hoursFactor;
    }

    public function ListOrders()
    {
    }
    public function render(){
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
            return view('livewire.tab1',['orders'=>$orders]);

        }
}
