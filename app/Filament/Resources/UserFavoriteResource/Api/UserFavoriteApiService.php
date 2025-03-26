<?php
namespace App\Filament\Resources\UserFavoriteResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\UserFavoriteResource;
use Illuminate\Routing\Router;


class UserFavoriteApiService extends ApiService
{
    protected static string | null $resource = UserFavoriteResource::class;

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
