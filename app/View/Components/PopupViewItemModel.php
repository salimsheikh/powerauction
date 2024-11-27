<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PopupViewItemModel extends Component
{
    public $title = '';
    public $popupClasses = '';
    public $popupId = '';
    /**
     * Create a new component instance.
     */
    public function __construct($title = '', $popupClasses = '', $popupId = 'popupViewItemModal')
    {
        $this->title = $title;      
        $this->popupClasses = $popupClasses;
        $this->popupId = $popupId;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.popup-view-item-model');
    }
}
