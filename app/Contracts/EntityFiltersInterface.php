<?php
namespace App\Contracts;

interface EntityFiltersInterface
{
    public function getFilters(): array;
    public function getSelectedFilters(): array;
}