<?php

namespace App\Filament\Resources\ReactionResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\ReactionResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\ReactionResource\Api\Transformers\ReactionTransformer;

class DetailHandler extends Handlers
{
    public static string|null $uri = '/{id}';
    public static string|null $resource = ReactionResource::class;
    public static bool $public = true;



    /**
     * Show Reaction
     *
     * @param Request $request
     * @return ReactionTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query)
            return static::sendNotFoundResponse();

        return new ReactionTransformer($query);
    }
}
