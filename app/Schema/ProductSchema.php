<?php

namespace App\Schema;


use App\Models\Catalog\Products;

class ProductSchema
{
    private Products $products;

    public function __construct(
        Products $product,
    )
    {
        $this->products = $product;

    }

    public function convertData()
    {
        $category = null;
        $brand = null;
        if ($this->products->category_id) {
            $category = [
                $this->products->category_id => $this->products->category->name,
            ];
        }
        if ($this->products->brand_id) {
            $brand = [
                $this->products->brand_id => $this->products->brand->name,
            ];
        }
        return [
            'id' => $this->products->id,
            'name' => $this->products->name,
            'category' => $category,
            'brand' => $brand,
            'slug' => $this->products->slug,
            'sku' => $this->products->sku,
            'release_date' => $this->products->release_date,
            'weight' => $this->products->weight,
            'magnetic' => $this->products->magnetic,
            'price' => $this->products->price,
            'box_weight' => $this->products->box_weight,
            'quantity' => $this->products->quantity,
            'unit' => $this->products->unit,
            'image' => $this->products->image,
            'created_at' => $this->products->created_at,
            'updated_at' => $this->products->deleted_at,
        ];
    }
}
