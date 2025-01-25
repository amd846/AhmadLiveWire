<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\RejectedOrder;
use App\Models\User;
use Carbon\Carbon;

class AcceptedTab extends Component
{
    public function render()
    {
        return view('livewire.accepted-tab');
    }

}