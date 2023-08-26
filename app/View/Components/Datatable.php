<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Datatable extends Component
{
    /**
     * Create a new component instance.
     */
    public $serverside;
    public function __construct($serverside = false)
    {
        $this->serverside = $serverside;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datatable', [
            'title' => $this->serverside
        ]);
    }
}
