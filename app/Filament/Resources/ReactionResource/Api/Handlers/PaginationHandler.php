<?php
namespace App\Filament\Resources\ReactionResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\ReactionResource;
use App\Filament\Resources\ReactionResource\Api\Transformers\ReactionTransformer;

class PaginationHandler extends Handlers
{
    public static string|null $uri = '/';
    public static string|null $resource = ReactionResource::class;
    public static bool $public = true;


    /**
     * List of Reaction
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

        return ReactionTransformer::collection($query);
    }
}
