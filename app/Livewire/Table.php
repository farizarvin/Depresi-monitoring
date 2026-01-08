<?php

namespace App\Livewire;

use Livewire\Component;

class Table extends Component
{
    /**
     * Create a new component instance.
     */
    // public $headers;
    // public function __construct(array $headers)
    // {
    //     //
    //     $this->headers=$headers;
        
    // }
    public function render()
    {
        return view('livewire.table');
    }
}
