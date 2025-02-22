<?php
namespace App\Filament\Resources\CategoryContentResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\CategoryContentResource;
use App\Filament\Resources\CategoryContentResource\Api\Transformers\CategoryContentTransformer;

class PaginationHandler extends Handlers
{
    public static string|null $uri = '/';
    public static string|null $resource = CategoryContentResource::class;
    public static bool $public = true;


    /**
     * List of CategoryContent
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function handler()
    {
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for($query)
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters($this->getAllowedFilters() ?? [])
            ->allowedIncludes($this->getAllowedIncludes() ?? [])
            ->paginate(request()->query('per_page'))
            ->appends(request()->query());

        return CategoryContentTransformer::collection($query);
    }
}
