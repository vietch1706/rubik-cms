<?php

namespace App\Schema;

use App\Models\Catalog\Brands;

class BrandSchema
{
    private Brands $brands;

    public function __construct(
        Brands $brand,
    )
    {
        $this->brands = $brand;

    }

    public function convertData()
    {
        return [
            'id' => $this->brands->id,
            'name' => $this->brands->name,
            'slug' => $this->brands->slug,
            'image' => $this->brands->image,
            'created_at' => $this->brands->created_at,
            'updated_at' => $this->brands->updated_at,
        ];
    }
}
