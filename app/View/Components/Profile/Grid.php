<?php

namespace App\View\Components\Profile;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Grid extends Component
{
    public function __construct(public $entities, public $entityName, public $entitiesName)
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.profile.grid');
    }
}
