<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashboardCard extends Component
{
    public $wrapperClass = '';
    public $iconBgColor = '';
    public $icon = '';
    public $value = '';
    public $title = '';

    /**
     * Create a new component instance.
     */
    public function __construct(
            $wrapperClass = '',
            $iconBgColor = '',
            $icon = '',
            $value = '',
            $title = ''
        )
    {
        $this->wrapperClass = $wrapperClass;
        $this->iconBgColor = $iconBgColor;
        $this->icon = $icon;
        $this->value = $value;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard-card');
    }
}
