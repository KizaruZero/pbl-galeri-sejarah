<?php
namespace App\Filament\Resources\UserReactionResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UserReaction;

/**
 * @property UserReaction $resource
 */
class UserReactionTransformer extends JsonResource
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
