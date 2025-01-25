<?php

namespace App\Livewire;

use Livewire\Component;
 use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\RejectedOrder;
use App\Models\AcceptedOrder;
use App\Models\Admin_Fixed;


use App\Models\User;
use Carbon\Carbon;

class NewOrder extends Component
{

    public $allOrders="";
    public $rejected="";
    public $accepted="";


    protected $listeners = ['ListOrders' => '$refresh',
    //'RejectedTab' => '$refresh'
];

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
   $this->getOrders();
   $this->getRejected();
 //  $this->getAccepted();
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

    $this->getOrders();
   $this->getRejected();
   $this->getAccepted();

}
public function acceptTwenty(){

    $inactiveUsers = User::whereBetween('age', [20, 30])->pluck('id');
    $cause = 'مقبول العمر بين 20 و30';

    $acceptedOrders = Order::whereIn('userID', $inactiveUsers)
        ->where('userTransferOrder', false)
        ->get();

    Order::whereIn('userID', $inactiveUsers)
        ->update([
            'userOrderStatus' => 'مقبول',
            'userTransferOrder' => true,
            'cause' => $cause,
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


   $this->getOrders();
  $this->getRejected();
  $this->getAccepted();

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

    public function getOrders(){
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
        //  dd($orders);
            $this->allOrders=$orders;

    }

    public function getRejected(){

        $activeSessions = $this->getActiveSessions();
        $rejectedorders = RejectedOrder::with('user:id,name,age,userLogIn_at,userLogOut_at')
        ->select([
            'id',
            'userID',
            'userRequiredAmount',
            'userOrderDate',
            'userPercentageAcceptance',
            'userOrderStatus',
            'cause',
        ])
      //  ->where('userTransferOrder', false)
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



$this->rejected= $rejectedorders;

    }

    public function getAccepted(){
        
    $activeSessions = $this->getActiveSessions();


            

    $acceptedOrders = AcceptedOrder::with('user:id,name,age,userLogIn_at,userLogOut_at')
    ->select([
        'id',
        'userID',
        'userRequiredAmount',
        'userOrderDate',
        'userPercentageAcceptance',
        'userOrderStatus',
        'cause',
    ])
  //  ->where('userTransferOrder', false)
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


   $this->accepted=$acceptedOrders ;
    }

    public function render()
    {
       $this->getOrders();
       $this->getRejected();
       $this->getAccepted();

            return view('livewire.new-order',[//);
             'orders' =>   $this->allOrders  ,
        'RejectedOrders'=>$this->rejected,
        'AcceptedOrders' => $this->accepted
          
    ]);
    }
}

