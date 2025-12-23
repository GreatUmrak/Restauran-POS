<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

class WaiterDashboardTest extends Component
{
    // Указываем layout
    #[Layout('livewire.layouts.app')]
    
    public function render()
    {
        return view('livewire.waiter-dashboard-test');
    }
}