<?php

namespace App\Filament\Resources\ContentVideoResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\ContentVideoResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\ContentVideoResource\Api\Transformers\ContentVideoTransformer;

class DetailHandler extends Handlers
{
    public static string|null $uri = '/{id}';
    public static string|null $resource = ContentVideoResource::class;


    /**
     * Show ContentVideo
     *
     * @param Request $request
     * @return ContentVideoTransformer
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

        return new ContentVideoTransformer($query);
    }
}
