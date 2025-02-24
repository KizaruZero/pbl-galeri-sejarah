<?php
namespace App\Traits;

trait Searchable
{
    public function scopeSearch($query, $searchTerm)
    {
        return $query->whereRaw(
            'MATCH(' . implode(',', $this->searchableFields) . ') AGAINST(? IN BOOLEAN MODE)',
            [$searchTerm]
        );
    }
}

?>