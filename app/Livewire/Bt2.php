<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Bt2 extends Component
{
    protected $listeners = ['ListOrder' => 'bt1Click'];
    public function render()
    {
       
        return view('livewire.bt2');
    }
    public function bt2Click(){
        $this->dispatch('funTab2');
        Log::info('This is an info log for Button 2.');
    }
}
