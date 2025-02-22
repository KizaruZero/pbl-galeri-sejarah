<?php
namespace App\Filament\Resources\ContentPhotoResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ContentPhotoResource;
use Illuminate\Routing\Router;


class ContentPhotoApiService extends ApiService
{
    protected static string | null $resource = ContentPhotoResource::class;

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
