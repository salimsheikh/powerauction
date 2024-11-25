<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PopupUpdateItemModel extends Component
{
    public $title = '';
    public $formType = '';
    public $formId = '';
    public $popupClasses = '';
    public $actionButtonLabel = '';
    /**
     * Create a new component instance.
     */
    public function __construct($title = '', $formType = '', $formId = 'popupAddForm', $popupClasses = '', $actionButtonLabel = 'Add Item')
    {
        $this->title = $title;
        $this->formType = $formType;
        $this->formId = $formId;
        $this->popupClasses = $popupClasses;
        $this->actionButtonLabel = $actionButtonLabel;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.popup-update-item-model');
    }
}
