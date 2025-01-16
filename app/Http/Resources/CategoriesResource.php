<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        parent::toArray($request);
        $parentCategory = null;
        if ($this->parent_id) {
            $parentCategory = [
                $this->parent_id => $this->parentCategory->name,
            ];
        }
        return [
            'id' => $this->id,
            'parent_category' => $parentCategory,
            'name' => $this->name,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
