<?php

namespace App\Filament\Resources\UserFavoriteResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\UserFavoriteResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\UserFavoriteResource\Api\Transformers\UserFavoriteTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = UserFavoriteResource::class;


    /**
     * Show UserFavorite
     *
     * @param Request $request
     * @return UserFavoriteTransformer
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

        return new UserFavoriteTransformer($query);
    }
}
