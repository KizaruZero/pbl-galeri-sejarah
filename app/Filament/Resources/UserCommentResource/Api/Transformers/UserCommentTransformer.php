<?php
namespace App\Filament\Resources\UserCommentResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UserComment;

/**
 * @property UserComment $resource
 */
class UserCommentTransformer extends JsonResource
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
