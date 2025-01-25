<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Bt3 extends Component
{
    protected $listeners = ['ListOrder' => 'bt1Click'];
    public function render()
    {
       
        return view('livewire.bt3');
    }
    public function bt3Click(){
        $this->dispatch('funTab3');
        Log::info('This is an info log for Button 3.');
    }
}
