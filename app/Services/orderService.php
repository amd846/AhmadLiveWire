<?php

namespace App\Services;


use App\Models\User;
use App\Models\Order;
use App\Models\AcceptedOrder;
use App\Models\RejectedOrder;
use App\Models\Admin_Fixed;


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