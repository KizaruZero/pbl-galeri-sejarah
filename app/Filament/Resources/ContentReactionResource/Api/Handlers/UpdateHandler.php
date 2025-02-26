<?php
namespace App\Filament\Resources\ContentReactionResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ContentReactionResource;
use App\Filament\Resources\ContentReactionResource\Api\Requests\UpdateContentReactionRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = ContentReactionResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update ContentReaction
     *
     * @param UpdateContentReactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateContentReactionRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}