<?php
namespace App\Filament\Resources\ContentReactionResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ContentReactionResource;
use Illuminate\Routing\Router;


class ContentReactionApiService extends ApiService
{
    protected static string | null $resource = ContentReactionResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
