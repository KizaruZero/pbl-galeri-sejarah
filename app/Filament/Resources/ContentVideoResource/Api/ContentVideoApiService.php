<?php
namespace App\Filament\Resources\ContentVideoResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ContentVideoResource;
use Illuminate\Routing\Router;


class ContentVideoApiService extends ApiService
{
    protected static string | null $resource = ContentVideoResource::class;

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
