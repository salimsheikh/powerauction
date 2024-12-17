<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GridPage extends Component
{
    public $addButtonLabel = "";
    public $addButtonPermission = "";
    public $searchTextboxPlaceholder = "";

    
    
    /**
     * Create a new component instance.
     */
    public function __construct($addButtonLabel = "", $addButtonPermission = "", $searchTextboxPlaceholder = "")
    {
        $this->addButtonLabel = $addButtonLabel;
        $this->addButtonPermission = $addButtonPermission;
        $this->searchTextboxPlaceholder = $searchTextboxPlaceholder == null ? __('Search for items') : $searchTextboxPlaceholder;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.grid-page');
    }
}
