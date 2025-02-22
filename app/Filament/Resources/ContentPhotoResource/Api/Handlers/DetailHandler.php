<?php

namespace App\Filament\Resources\ContentPhotoResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\ContentPhotoResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\ContentPhotoResource\Api\Transformers\ContentPhotoTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = ContentPhotoResource::class;


    /**
     * Show ContentPhoto
     *
     * @param Request $request
     * @return ContentPhotoTransformer
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

        return new ContentPhotoTransformer($query);
    }
}
