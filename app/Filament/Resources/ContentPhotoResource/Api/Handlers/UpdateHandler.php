<?php
namespace App\Filament\Resources\ContentPhotoResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ContentPhotoResource;
use App\Filament\Resources\ContentPhotoResource\Api\Requests\UpdateContentPhotoRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = ContentPhotoResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update ContentPhoto
     *
     * @param UpdateContentPhotoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateContentPhotoRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}