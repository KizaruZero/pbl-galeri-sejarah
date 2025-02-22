<?php
namespace App\Filament\Resources\UserReactionResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\UserReactionResource;
use Illuminate\Routing\Router;


class UserReactionApiService extends ApiService
{
    protected static string | null $resource = UserReactionResource::class;

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
