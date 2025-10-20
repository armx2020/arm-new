<?php
namespace App\Contracts;

interface EntityColumnsInterface
{
    public function getAllColumns(): array;
    public function getSelectedColumns(): array;
}