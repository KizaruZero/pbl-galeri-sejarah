<?php
namespace App\Filament\Resources\ContentReactionResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ContentReaction;

/**
 * @property ContentReaction $resource
 */
class ContentReactionTransformer extends JsonResource
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
