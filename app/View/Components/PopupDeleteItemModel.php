<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PopupDeleteItemModel extends Component
{
    public $title = '';

    public $buttonTitle = "";
    /**
     * Create a new component instance.
     */
    public function __construct($title = '', $buttonTitle = '')
    {
        $this->title = $title;

        $this->buttonTitle = $buttonTitle == "" ? __('Delete') : $buttonTitle;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.popup-delete-item-model');
    }
}
