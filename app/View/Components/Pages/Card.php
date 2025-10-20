<?php

namespace App\View\Components\Pages;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public function __construct(public $entity, public $entityShowRoute)
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.pages.card');
    }
}
