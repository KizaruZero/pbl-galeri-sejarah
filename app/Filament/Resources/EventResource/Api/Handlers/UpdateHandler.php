<?php
namespace App\Filament\Resources\EventResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\EventResource;
use App\Filament\Resources\EventResource\Api\Requests\UpdateEventRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{slug}';
    public static string | null $resource = EventResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Event
     *
     * @param UpdateEventRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateEventRequest $request)
    {
        $slug = $request->route('slug');

        $model = static::getModel()::find($slug);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}