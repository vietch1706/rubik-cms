<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ProductsResource extends JsonResource
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
        $category = null;
        $brand = null;
        if ($this->category_id) {
            $category = $this->category()->pluck('name', 'id')->toArray();
        }
        if ($this->brand_id) {
            $brand = $this->brand()->pluck('name', 'id')->toArray();
        }
        $gallery = $this->galleries()->pluck('image', 'id')->toArray();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $category,
            'brand' => $brand,
            'slug' => $this->slug,
            'distributor' => $this
                ->distributors()
                ->where('price', $this->price)
                ->pluck('name', 'id')
                ->toArray(),
            'status' => $this->status,
            'sku' => $this->sku,
            'release_date' => $this->release_date,
            'weight' => $this->weight,
            'magnetic' => $this->textMagnetic,
            'price' => $this->price,
            'box_weight' => $this->box_weight,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'image' => $this->image,
            'gallery' => $gallery,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
