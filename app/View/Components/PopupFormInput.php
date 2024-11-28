<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Style;
use App\Models\SponsorType;
use App\Models\League;
use App\Models\Plan;

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
    public $placeholder = "";

    /**
     * Create a new component instance.
     */
    public function __construct($type = 'text', $name = 'inputname', $id = '', $label = 'label', $class = '', $value = '', $mexlength = '', $placeholder = "")
    {
        $id = $id == "" ? $name : $id;

        $this->type = $type;
        $this->name = $name;
        $this->id = $id;
        $this->label = $label;
        $this->class = $class != "" ? $class : $id;
        $this->value = $value;
        $this->mexlength = ($mexlength == null || $mexlength < 0) ? 191 : $mexlength;
        $this->placeholder = ($placeholder == null || $placeholder == "") ? Str::replaceLast(':', '', $label) : $placeholder ;
        $this->options = [];
        $this->firstOption = '';

        if($type == 'select'){
            switch($name){
                case "category_id":
                    $this->firstOption = __('Select Category');
                    $items = Category::select('id','category_name')->where('status', 'publish')->orderBy('category_name', 'ASC')->get();               
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
                    /*
                    $items = Style::orderBy('order', 'ASC')->get();               
                    foreach($items as $item){
                        $this->options[$item->slug] = $item->name;
                    }
                    */
                    $this->options =[
                        "heft_hand_batsman" => __('Left Hand Batsman'),
                        "right_hand_batsman" => __('Right Hand Batsman'),
                        "left_hand_bowler" => __('Left Hand Bowler'),
                        "right_hand_bowler" =>__('Right Hand Bowler')
                    ];;
                    break;
                case "sponsor_type":
                    $this->firstOption = __('Select Type');
                    $items = SponsorType::select('slug','name')->orderBy('order', 'ASC')->get();               
                    foreach($items as $item){
                        $this->options[$item->slug] = $item->name;
                    }
                    /*
                    $this->options = [
                        "premium" => __('Premium'),
                        "gold" => __('Gold')
                    ];
                    */
                    break;
                case "league_id":
                    $this->firstOption = __('Select League');
                    $items = League::select('id','league_name')->where('status', '1')->orderBy('league_name', 'ASC')->get();               
                    foreach($items as $item){
                        $this->options[$item->id] = $item->league_name;
                    }
                    break;
                case "plan_type":
                    $this->firstOption = __('Select plan');
                    $items = Plan::select('id','name')->where('status', 'publish')->orderBy('order', 'ASC')->get();
                    foreach($items as $item){
                        $this->options[$item->id] = $item->name;
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
