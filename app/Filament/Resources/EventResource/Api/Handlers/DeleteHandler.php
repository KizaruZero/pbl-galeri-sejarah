<?php
namespace App\Filament\Resources\EventResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\EventResource;

class DeleteHandler extends Handlers {
    public static string | null $uri = '/{slug}';
    public static string | null $resource = EventResource::class;

    public static bool $public = true;


    public static function getMethod()
    {
        return Handlers::DELETE;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Delete Event
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(Request $request)
    {
        $slug = $request->route('slug');

        $model = static::getModel()::find($slug);

        if (!$model) return static::sendNotFoundResponse();

        $model->delete();

        return static::sendSuccessResponse($model, "Successfully Delete Resource");
    }
}