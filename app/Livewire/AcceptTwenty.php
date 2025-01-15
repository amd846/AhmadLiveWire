<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\AcceptedOrder;
use App\Models\User;

class AcceptTwenty extends Component
{
    protected $listeners = ['funAcceptTwenty' => 'acceptTwenty'];

    public function render()
    {
        return view('livewire.accept-twenty');
    }

    public function acceptTwenty()
    {
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

        $this->dispatch('ListOrder');
    }
}
