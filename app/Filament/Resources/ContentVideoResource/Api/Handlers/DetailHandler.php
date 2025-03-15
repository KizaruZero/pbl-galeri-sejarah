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
    public static string|null $uri = '/{slug}';
    public static string|null $resource = ContentVideoResource::class;
    public static bool $public = true;


    /**
     * Show ContentVideo
     *
     * @param Request $request
     * @return ContentVideoTransformer
     */
    public function handler(Request $request)
    {
        $slug = $request->route('slug');

        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where('slug', $slug) // Ganti getKeyName() dengan 'slug'
        )
            ->first();

        if (!$query)
            return static::sendNotFoundResponse();

        return new ContentVideoTransformer($query);
    }
}
