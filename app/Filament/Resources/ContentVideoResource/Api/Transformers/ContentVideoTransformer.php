<?php
namespace App\Filament\Resources\ContentVideoResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ContentVideo;

/**
 * @property ContentVideo $resource
 */
class ContentVideoTransformer extends JsonResource
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
