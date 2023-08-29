<?php

namespace App\View\Components;

use App\Models\Pengaturan;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Head extends Component
{
    /**
     * Create a new component instance.
     */
    public $title;
    public function __construct($title = 'Sistem Koperasi')
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.head', [
            'pengaturan' => Pengaturan::first(),
            'title' => $this->title
        ]);
    }
}
