<?php
namespace App\Filament\Resources\UserFavoriteResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UserFavorite;

/**
 * @property UserFavorite $resource
 */
class UserFavoriteTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
