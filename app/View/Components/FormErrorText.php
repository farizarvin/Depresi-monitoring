<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormErrorText extends Component
{
    /**
     * Create a new component instance.
     */


    public $field='';
    public function __construct(string $field)
    {
        //
        $this->field=$field;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-error-text');
    }
}
