<?php
namespace App\Filament\Resources\ContentPhotoResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ContentPhotoResource;
use App\Filament\Resources\ContentPhotoResource\Api\Requests\CreateContentPhotoRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = ContentPhotoResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create ContentPhoto
     *
     * @param CreateContentPhotoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateContentPhotoRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}