<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class pagination extends Component
{
    public $items = null;
    /**
     * Create a new component instance.
     */
    public function __construct($items = null)
    {
        $this->items = $items;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.pagination');
    }
}
