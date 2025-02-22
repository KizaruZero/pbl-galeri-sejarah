<?php
namespace App\Filament\Resources\UserCommentResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\UserCommentResource;
use App\Filament\Resources\UserCommentResource\Api\Requests\UpdateUserCommentRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = UserCommentResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update UserComment
     *
     * @param UpdateUserCommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateUserCommentRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}