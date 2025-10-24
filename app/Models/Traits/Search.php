<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\DB;

trait Search
{
    private function buildWildCards($term) {
        if ($term == "") {
            return $term;
        }
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);
        $words = explode(' ', $term);
        foreach($words as $idx => $word) {
            if($word != ""){
                $words[$idx] = "+" . $word . "*";
            }
        }
        $term = implode(' ', $words);
        return $term;
    }

    protected function scopeSearch($query, $term) {
        $columns = implode(',', $this->searchable);
        $driver = config('database.default');

        if ($driver === 'pgsql') {
            $tsColumns = implode(" || ' ' || ", array_map(function($col) {
                return "COALESCE({$col}, '')";
            }, $this->searchable));
            
            $searchTerm = str_replace(' ', ' & ', trim($term));
            
            $query->whereRaw(
                "to_tsvector('simple', {$tsColumns}) @@ to_tsquery('simple', ?)",
                [$searchTerm]
            );
        } else {
            $query->whereRaw(
                "MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)",
                $this->buildWildCards($term)
            );
        }
        
        return $query;
    }
}
