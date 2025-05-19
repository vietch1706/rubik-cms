<?php

namespace App\Schema;

use App\Schema\Base\AbstractConvertData;
use Illuminate\Database\Eloquent\Model;

class ProductSchema extends AbstractConvertData
{
    public function convertData(Model $model): array
    {
        $gallery = $model->galleries()->pluck('image', 'id')->toArray();
        return [
            'id' => $model->id,
            'name' => $model->name,
            'category' => $model->category()->pluck('name', 'id')->toArray(),
            'brand' => $model->brand()->pluck('name', 'id')->toArray(),
            'slug' => $model->slug,
            'distributor' => $model
                ->distributors()
                ->where('price', $model->price)
                ->pluck('name', 'id')
                ->toArray(),
            'status' => $model->status,
            'sku' => $model->sku,
            'release_date' => $model->release_date,
            'weight' => $model->weight,
            'magnetic' => $model->magnetic,
            'price' => $model->price,
            'box_weight' => $model->box_weight,
            'quantity' => $model->quantity,
            'unit' => $model->unit,
            'image' => $model->image,
            'gallery' => $gallery,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
        ];

    }
}
