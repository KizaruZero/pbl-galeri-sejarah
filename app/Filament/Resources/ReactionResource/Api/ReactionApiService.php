<?php
namespace App\Filament\Resources\ReactionResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ReactionResource;
use Illuminate\Routing\Router;


class ReactionApiService extends ApiService
{
    protected static string | null $resource = ReactionResource::class;

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
