<?php
namespace App\Filament\Resources\UserCommentResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\UserCommentResource;
use App\Filament\Resources\UserCommentResource\Api\Requests\CreateUserCommentRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = UserCommentResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create UserComment
     *
     * @param CreateUserCommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateUserCommentRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}