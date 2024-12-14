<?php

namespace App\Schema;

use App\Models\Catalogs\Categories;

class CategorySchema
{
    private Categories $categories;

    public function __construct(
        Categories $category,
    )
    {
        $this->categories = $category;

    }

    public function convertData()
    {
        $parentCategory = null;
        if ($this->categories->parent_id) {
            $parentCategory = [
                $this->categories->parent_id => $this->categories->parentCategory->name,
            ];
        }
        return [
            'id' => $this->categories->id,
            'parent_category' => $parentCategory,
            'name' => $this->categories->name,
            'slug' => $this->categories->slug,
            'created_at' => $this->categories->created_at,
            'updated_at' => $this->categories->updated_at,
        ];
    }
}
