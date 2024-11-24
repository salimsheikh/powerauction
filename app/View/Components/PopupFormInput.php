<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Category;
use App\Models\Style;

class PopupFormInput extends Component
{
    public $type = "";
    public $name = "";
    public $id = "";
    public $class = "";
    public $label = "";
    public $value = "";
    public $mexlength = "";
    public $options = "";
    public $firstOption = "";

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
        $this->options = [];
        $this->firstOption = '';

        if($type == 'select'){
            switch($name){
                case "category":
                    $this->firstOption = __('Select Category');
                    $items = Category::where('status', 'publish')->orderBy('category_name', 'ASC')->get();               
                    foreach($items as $item){
                        $this->options[$item->id] = $item->category_name;
                    }
                    break;
                case "profile_type":
                    $this->firstOption = __('Select Profile');
                    $this->options = [
                        "men" => __('Men'),
                        "women" => __('Women'),
                        "senior-citizen" => __('Senior Citizen')
                    ];
                    break;
                case "type":
                    $this->firstOption = __('Select Type');
                    $this->options = [
                        "batsman" => __('Batsman'),
                        "bowler" => __('Bowler'),
                        "all-rounder" => __('All Rounder')
                    ];
                    break;
                case "style":                
                    $this->firstOption = __('Select Type');
                    $this->firstOption = __('Select Category');
                    $items = Style::orderBy('order', 'ASC')->get();               
                    foreach($items as $item){
                        $this->options[$item->slug] = $item->name;
                    }
                    break;    
            }
        }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.popup-form-input');
    }
}
