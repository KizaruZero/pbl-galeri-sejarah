<?php
namespace App\Filament\Resources\ContentPhotoResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ContentPhoto;

/**
 * @property ContentPhoto $resource
 */
class ContentPhotoTransformer extends JsonResource
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
