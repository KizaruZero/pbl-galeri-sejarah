<?php
namespace App\Filament\Resources\UserReactionResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\UserReactionResource;
use App\Filament\Resources\UserReactionResource\Api\Requests\CreateUserReactionRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = UserReactionResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create UserReaction
     *
     * @param CreateUserReactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateUserReactionRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}