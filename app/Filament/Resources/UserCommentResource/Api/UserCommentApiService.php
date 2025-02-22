<?php
namespace App\Filament\Resources\UserCommentResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\UserCommentResource;
use Illuminate\Routing\Router;


class UserCommentApiService extends ApiService
{
    protected static string | null $resource = UserCommentResource::class;

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
