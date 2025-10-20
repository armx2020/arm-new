<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Filters extends Component
{
    public function __construct(public $filters)
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.admin.filters');
    }
}
