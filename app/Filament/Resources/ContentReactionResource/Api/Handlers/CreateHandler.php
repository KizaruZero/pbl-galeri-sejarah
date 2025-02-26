<?php
namespace App\Filament\Resources\ContentReactionResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ContentReactionResource;
use App\Filament\Resources\ContentReactionResource\Api\Requests\CreateContentReactionRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = ContentReactionResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create ContentReaction
     *
     * @param CreateContentReactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateContentReactionRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}