<?php

namespace App\Services;


use App\Models\User;
use App\Models\Order;
use App\Models\AcceptedOrder;
use App\Models\RejectedOrder;
use App\Models\Admin_Fixed;

use Carbon\Carbon;


//use App\Contracts\PaymentGatewayInterface;

class orderService
{
    //protected PaymentGatewayInterface $paymentGateway;

    /**
     * PaymentService constructor.
     *
     * @param PaymentGatewayInterface $paymentGateway
     */
    public function __construct( )
    {
       // $this->paymentGateway = $paymentGateway;
    }

    
    public function moreSixty() {
        try {
            $activeUsers = User::where('userLogIn_at', '>', now()->subDays(2))
                ->where('age', '<', 25)
                ->pluck('id');
    
            $fixedMoney = Admin_Fixed::first()->fixedMoney;
            $cause='النسبة فوق الـ 60%';
            $acceptedOrders = Order::whereIn('userID', $activeUsers)
                ->where('userRequiredAmount', '>', $fixedMoney)
                ->get();
    
            if ($acceptedOrders->isNotEmpty()) {
                Order::whereIn('userID', $activeUsers)
                    ->update([
                        'userOrderStatus' => 'مقبول',
                        'userTransferOrder' => true,
                    ]);
            }
            $this->addAcceptedOrders( $acceptedOrders,$cause);
            // Log and return success
            \Log::info('Processed accepted orders:', ['acceptedOrders' => $acceptedOrders->toArray()]);
            return response()->json(['success' => true, 'message' => 'Orders processed successfully']);
        } catch (\Exception $e) {
            \Log::error('Error in moreSixty method:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }
    

    public function rejectedOrders(){
        

        $orders = RejectedOrder::with('user')
            ->select([
                'id', // Ensure 'id' is selected for 'OrderID'
                'userID', 
                'userRequiredAmount',
                'userOrderDate',
                'userPercentageAcceptance',
                'userOrderStatus',
                'cause', // Assuming there's a 'reason' column in the orders table
                 
            ])
            ->where('userTransferOrder', false)
            ->get()
            ->map(function ($order) {
                $percentageAcceptance = $order->userPercentageAcceptance;
                $percentageAcceptanceAge=0;
                $percentageAcceptanceMoney=0;
                $percentageAcceptanceHours=0;
        
                // Update PercentageAcceptance if age > 20
                if ($order->user && $order->user->age < 30) {
                   // $percentageAcceptance = 20;
                   $percentageAcceptanceAge=20;
                } else if ($order->user && $order->user->age < 25) {
                   // $percentageAcceptance = 30;
                   $percentageAcceptanceAge=30;
                }  
        
                if ($order->userRequiredAmount > 100) {
                    // $percentageAcceptance = 20;
                    $percentageAcceptanceMoney=30;
                 }
         
                 if ($order->user ) {
                    if($order->user->userLogIn_at > $order->user->userLogOut_at){
                        $loginTime = Carbon::parse($order->user->userLogIn_at);
                        $hoursDifference = $loginTime->diffInHours(Carbon::now());
                        if($hoursDifference < 24){
                    // $percentageAcceptance = 20;
                            $percentageAcceptanceHours=20;
                        }
                    }
                 }
        
                 $percentageAcceptance =    $percentageAcceptanceAge +    $percentageAcceptanceMoney +    $percentageAcceptanceHours;
        
        
                return [
                    'UserName' => $order->user->name ?? 'غير متوفر',
                    'RequiredAmount' => $order->userRequiredAmount,
                    'OrderID' => $order->id,
                    'OrderDate' => $order->userOrderDate->format('Y-m-d H:i:s'),
                    'PercentageAcceptance' => $percentageAcceptance,//$order->userPercentageAcceptance,
                    'OrderStatus' => $order->userOrderStatus,
                    'Reason' => $order->cause ?? ' ',
                    'Age'=>$order->user->age
                ];
            });


            return $orders;
        
    }


    public function deleteThreeDays(){
        \Log::info('Delete Three Days');
        $inactiveUsers = User::where('userLogIn_at', '<', now()->subDays(3))
        ->pluck('id');
            $rejectedOrders = Order::whereIn('userID', $inactiveUsers)
        ->where('userTransferOrder', false)
        ->get();  
         $cause = 'لم يتم الدخول لاكثر من ثلاثة ايام';
          // Update orders for these users
          if ($rejectedOrders->isNotEmpty()) {
        Order::whereIn('userID',  $inactiveUsers )
            ->update([
                'userOrderStatus' => 'مرفوض',
                'userTransferOrder' => true,
                'cause' => $cause,
            ]);
    
        // Call the addRejectedOrders method
        $this->addRejectedOrders($rejectedOrders, $cause);   
        } 
    }



    public function addRejectedOrders($rejectedOrders, $cause){
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


    
public function addAcceptedOrders($acceptedOrders, $cause){
    foreach ($acceptedOrders as $order) {
        AcceptedOrder::create([
            'userOrderNumber' => $order->id,
            'userID' => $order->userID,
            'userRequiredAmount' => $order->userRequiredAmount,
            'userPercentageAcceptance' => $order->userPercentageAcceptance,
            'userOrderStatus' => 'مقبول',
            'userOrderDate' => $order->userOrderDate,
            'cause' => $cause
           // 'userTransferOrder' => $order->userTransferOrder,
        ]);
    }  
}

}