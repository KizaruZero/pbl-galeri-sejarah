<?php
namespace App\Filament\Resources\CategoryContentResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\CategoryContent;

/**
 * @property CategoryContent $resource
 */
class CategoryContentTransformer extends JsonResource
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
