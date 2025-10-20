<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Columns extends Component
{
    public function __construct(public array $allColumns)
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.admin.columns');
    }
}
