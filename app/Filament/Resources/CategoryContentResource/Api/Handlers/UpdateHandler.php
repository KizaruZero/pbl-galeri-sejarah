<?php
namespace App\Filament\Resources\CategoryContentResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\CategoryContentResource;
use App\Filament\Resources\CategoryContentResource\Api\Requests\UpdateCategoryContentRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = CategoryContentResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update CategoryContent
     *
     * @param UpdateCategoryContentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateCategoryContentRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}