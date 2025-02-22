<?php
namespace App\Filament\Resources\CategoryContentResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\CategoryContentResource;
use Illuminate\Routing\Router;


class CategoryContentApiService extends ApiService
{
    protected static string | null $resource = CategoryContentResource::class;

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
