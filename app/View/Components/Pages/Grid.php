<?php

namespace App\View\Components\Pages;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Grid extends Component
{
    public function __construct(public $entities, public $entityShowRoute, public $region)
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.pages.grid');
    }
}
