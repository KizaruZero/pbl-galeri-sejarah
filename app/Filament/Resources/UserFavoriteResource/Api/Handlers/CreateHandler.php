<?php
namespace App\Filament\Resources\UserFavoriteResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\UserFavoriteResource;
use App\Filament\Resources\UserFavoriteResource\Api\Requests\CreateUserFavoriteRequest;

class CreateHandler extends Handlers
{
    public static string|null $uri = '/';
    public static string|null $resource = UserFavoriteResource::class;
    public static bool $public = true;


    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel()
    {
        return static::$resource::getModel();
    }

    /**
     * Create UserFavorite
     *
     * @param CreateUserFavoriteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateUserFavoriteRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}