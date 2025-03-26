<?php
namespace App\Filament\Resources\UserFavoriteResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\UserFavoriteResource;
use App\Filament\Resources\UserFavoriteResource\Api\Requests\UpdateUserFavoriteRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = UserFavoriteResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update UserFavorite
     *
     * @param UpdateUserFavoriteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateUserFavoriteRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}