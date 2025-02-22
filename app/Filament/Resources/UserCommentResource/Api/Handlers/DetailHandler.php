<?php

namespace App\Filament\Resources\UserCommentResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\UserCommentResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\UserCommentResource\Api\Transformers\UserCommentTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = UserCommentResource::class;


    /**
     * Show UserComment
     *
     * @param Request $request
     * @return UserCommentTransformer
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

        return new UserCommentTransformer($query);
    }
}
