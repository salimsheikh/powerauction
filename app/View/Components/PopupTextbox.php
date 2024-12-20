<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PopupTextbox extends Component
{
    public $type = "";
    public $name = "";
    public $class = "";
    public $label = "";
    public $value = "";
    public $maxlength = "";

    /**
     * Create a new component instance.
     */
    public function __construct($type = 'text', $name = 'inputname', $label = 'label', $class = '', $value = '', $maxlength = '100')
    {
        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
        $this->class = $class;
        $this->value = $value;
        $this->maxlength = $maxlength;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.popup-textbox');
    }
}
