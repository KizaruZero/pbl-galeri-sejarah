<?php
namespace App\Filament\Resources\ReactionResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ReactionResource;
use App\Filament\Resources\ReactionResource\Api\Requests\UpdateReactionRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = ReactionResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Reaction
     *
     * @param UpdateReactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateReactionRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}