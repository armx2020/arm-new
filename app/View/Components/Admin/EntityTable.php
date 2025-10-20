<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EntityTable extends Component
{
    public function __construct(
        public $entities,
        public array $allColumns,
        public array $selectedColumns,
        public array $filters,
        public string $title,
        public string $emptyEntity,
        public string $entityName,
        public array $colorMap = [],
    ) {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.admin.entity-table');
    }
}
