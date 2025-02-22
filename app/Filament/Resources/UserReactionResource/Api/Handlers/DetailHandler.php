<?php

namespace App\Filament\Resources\UserReactionResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\UserReactionResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\UserReactionResource\Api\Transformers\UserReactionTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = UserReactionResource::class;


    /**
     * Show UserReaction
     *
     * @param Request $request
     * @return UserReactionTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new UserReactionTransformer($query);
    }
}
