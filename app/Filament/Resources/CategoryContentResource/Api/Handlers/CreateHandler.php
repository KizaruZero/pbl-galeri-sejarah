<?php
namespace App\Filament\Resources\CategoryContentResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\CategoryContentResource;
use App\Filament\Resources\CategoryContentResource\Api\Requests\CreateCategoryContentRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = CategoryContentResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create CategoryContent
     *
     * @param CreateCategoryContentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateCategoryContentRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}