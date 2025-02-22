<?php
namespace App\Filament\Resources\ContentVideoResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ContentVideoResource;
use App\Filament\Resources\ContentVideoResource\Api\Requests\CreateContentVideoRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = ContentVideoResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create ContentVideo
     *
     * @param CreateContentVideoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateContentVideoRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}