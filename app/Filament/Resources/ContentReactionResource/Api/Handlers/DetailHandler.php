<?php

namespace App\Filament\Resources\ContentReactionResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\ContentReactionResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\ContentReactionResource\Api\Transformers\ContentReactionTransformer;

class DetailHandler extends Handlers
{
    public static string|null $uri = '/{id}';
    public static string|null $resource = ContentReactionResource::class;
    public static bool $public = true;



    /**
     * Show ContentReaction
     *
     * @param Request $request
     * @return ContentReactionTransformer
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

        return new ContentReactionTransformer($query);
    }
}
