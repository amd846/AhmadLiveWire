<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Bt1 extends Component
{
    protected $listeners = ['ListOrder' => 'bt1Click'];
    public function render()
    {
        
        return view('livewire.bt1');
    }
    public function bt1Click(){
       //  return view('livewire.tab1');
        $this->dispatch('funTab1');
       Log::info('This is an info log for Button 1.');
    }
}
