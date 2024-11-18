<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PopupFormInput extends Component
{
    public $type = "";
    public $name = "";
    public $id = "";
    public $class = "";
    public $label = "";
    public $value = "";
    public $mexlength = "";

    /**
     * Create a new component instance.
     */
    public function __construct($type = 'text', $name = 'inputname', $id = '', $label = 'label', $class = '', $value = '', $mexlength = '100')
    {
        $id = $id == "" ? $name : $id;

        $this->type = $type;
        $this->name = $name;
        $this->id = $id;
        $this->label = $label;
        $this->class = $class != "" ? $class : $id;
        $this->value = $value;
        $this->mexlength = $mexlength != '' ? $mexlength : 191;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.popup-form-input');
    }
}
