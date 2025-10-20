<?php

namespace App\View\Components\Pages;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EntityCard extends Component
{
    public function __construct(public $entity, public $entityTypeUrl)
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.pages.entity-card');
    }
}
