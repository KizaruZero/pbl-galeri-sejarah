<?php

namespace App\Filament\Resources\CategoryContentResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\CategoryContentResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\CategoryContentResource\Api\Transformers\CategoryContentTransformer;

class DetailHandler extends Handlers
{
    public static string|null $uri = '/{id}';
    public static string|null $resource = CategoryContentResource::class;
    public static bool $public = true;



    /**
     * Show CategoryContent
     *
     * @param Request $request
     * @return CategoryContentTransformer
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

        return new CategoryContentTransformer($query);
    }
}
