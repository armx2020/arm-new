<?php

namespace App\View\Components\Profile;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public function __construct(public $entity, public $entityName, public $entitiesName)
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.profile.card');
    }
}
