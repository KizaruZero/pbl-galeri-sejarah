<?php

namespace App\Filament\Resources\ArticleResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\ArticleResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\ArticleResource\Api\Transformers\ArticleTransformer;

class DetailHandler extends Handlers
{
    public static string|null $uri = '/{slug}';
    public static string|null $resource = ArticleResource::class;
    public static bool $public = true;



    /**
     * Show Article
     *
     * @param Request $request
     * @return ArticleTransformer
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

        return new ArticleTransformer($query);
    }
}
