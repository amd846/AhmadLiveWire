<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\AcceptedOrder;
use App\Models\RejectedOrder;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Admin_Fixed;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('web')->user();   
        $orders = Order::where('userID', $user->id)->first();
        //
        //$orders="";
        return view('order.showOrder',['orders'=>$orders]);

    }

    /**
     * Show the form for creating a new resource.
     */

     public function CountOrders($user){
        $ordersCount = Order::where('userID', $user->id)->count();
        return $ordersCount;
     }
     public function createOrder(Request $request, $user)
     {
        
        $newOrder = Order::create([
     

           // 'userOrderNumber' => 1,
            'userID'=>$user->id,
            'userRequiredAmount'=>$request->userRequiredAmount,
             'userPercentageAcceptance'=>1,
            'userOrderStatus'=>'معلق',
            'userOrderDate'=>now(),
            'userTransferOrder'=>false,
        ]);
        return $newOrder;
     }

    public function create(Request $request)
    {
        return view('order.create');
         
       
  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        $user = Auth::guard('web')->user();   
        //
            // Call the CountOrders method
    $ordersCount = $this->CountOrders($user);

    // Debug and display the count
   
   if ($ordersCount == 0) {
    $createdOrder = $this->createOrder($request,$user );
 } else {
    return response()->json(['Sorry' => true ] );
}
return redirect()->route('order.showOrder')->with('success', 'Order is created successfully.');
       // return response()->json(['success' => true ], 201);
    }

    public function NewOrder(){ 
        return view('supervisor.NewOrder');
    }


    public function Test(){ 
        return view('supervisor.test');
    }

    public function showOrderSupervisorLivewire(){ 

        $activeSessions = DB::table('sessions')
        ->whereNotNull('user_id')
        ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
        ->pluck('user_id');





        $orders = Order::with('user')
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
    ->map(function ($order) use ($activeSessions) {
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
            'Age'=>$order->user->age,
            'OnOff' => in_array($order->userID, $activeSessions->toArray()) ? 'نشط' : 'غير نشط',
            'UserID' => $order->userID, // Include userID here


        ];
    });

   $rejectedOrders=$this->rejectedOrders();
   //, 'rejectedOrders'=>$rejectedOrders
    return view('supervisor.showOrderWire',['orders'=>$orders,'rejectedOrders'=>$rejectedOrders]);
// To debug or return the data
//return response()->json($results);

    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
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
        
    }


    public function showAcceptedOrderSupervisor(){

        
        

        $orders = AcceptedOrder::with('user')
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
        
         
            return view('supervisor.showٌAcceptedOrder',['orders'=>$orders]);
        // To debug or return the data
        //return response()->json($results);
        
            }





    public function showRejectedOrderSupervisor(){

        
        

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
        
         
            return view('supervisor.showٌRejectedOrder',['orders'=>$orders]);
        // To debug or return the data
        //return response()->json($results);
        
            }






    public function showOrderSupervisor(){ 

        $activeSessions = DB::table('sessions')
        ->whereNotNull('user_id')
        ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
        ->pluck('user_id');





        $orders = Order::with('user')
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
    ->map(function ($order) use ($activeSessions) {
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
            'Age'=>$order->user->age,
            'OnOff' => in_array($order->userID, $activeSessions->toArray()) ? 'نشط' : 'غير نشط',
            'UserID' => $order->userID, // Include userID here


        ];
    });

 
    return view('supervisor.showOrder',['orders'=>$orders]);
// To debug or return the data
//return response()->json($results);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }




    public function inActiveThreeDays(){
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
         return $this->showOrderSupervisor();
       /* if (now()->diffInMinutes(session('last_activity')) > config('session.lifetime'))
        {

        }*/
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
    







    public function twoDays(){
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
        $this->addRejectedOrders($rejectedOrders, $cause);   
       }
 
        return $this->showOrderSupervisor();
       /* if (now()->diffInMinutes(session('last_activity')) > config('session.lifetime'))
        {

        }*/
    }

public function   adminMoney(){
    $fixedMoney = Admin_Fixed::first()->fixedMoney;

    $cause= 'مبلغ من الادمن';
    $acceptedOrders = Order::where('userRequiredAmount', '>', $fixedMoney)
    ->where('userTransferOrder', false)
    ->get();

    $this->addAcceptedOrders( $acceptedOrders,$cause);

    // Update orders where the userRequiredAmount is greater than the fixedMoney
     Order::where('userRequiredAmount', '>', $fixedMoney)
        ->update([
            'userOrderStatus' => 'مقبول',
                'userTransferOrder' => true,
            'cause' => 'مبلغ من الادمن'
        ]);
 
        return $this->showOrderSupervisor();
}


public function betweenAge() {
    $inactiveUsers = User::whereBetween('age', [20, 30])
    ->pluck('id');
    $cause='مقبول العمر بين 20 و30';

    //$acceptedOrders= Order::whereIn(['userID', $inactiveUsers])->get();


    $acceptedOrders = Order::whereIn('userID', $inactiveUsers)
    ->where('userTransferOrder', false)
    ->get();
   

    // Update orders for these users
     $acceptedOrders1= Order::whereIn('userID', $inactiveUsers)
       ->update(['userOrderStatus' => 'مقبول',
                 'userTransferOrder' => true,
                   'cause' =>$cause]);

  $this->addAcceptedOrders( $acceptedOrders,$cause);
       return $this->showOrderSupervisor();

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


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      
      $order = Order::where('id', $id)->first();
        
        // Check if the order exists
        if ($order) {
            $order->delete(); // Delete the order
            return redirect()->route('order.showOrder')->with('success', 'Order deleted successfully.');
        } else {
            return redirect()->route('order.showOrder')->with('error', 'Order not found.');
        }
    }
}
