<?php
namespace App\Filament\Resources\UserReactionResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\UserReactionResource;
use App\Filament\Resources\UserReactionResource\Api\Requests\UpdateUserReactionRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = UserReactionResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update UserReaction
     *
     * @param UpdateUserReactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateUserReactionRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}