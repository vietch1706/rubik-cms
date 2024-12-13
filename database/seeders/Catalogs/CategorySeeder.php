<?php

namespace Database\Seeders\Catalogs;

use App\Models\Catalog\Categories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use function strtolower;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $categories = [
            [
                'id' => 1,
                'name' => 'WCA Rubik\'s Cube Tournament',
            ],
            [
                'id' => 2,
                'name' => 'Rubik\'s Cube Collection'
            ],
            [
                'id' => 3,
                'name' => 'Rubik\'s Cube Variant'
            ],
            [
                'id' => 4,
                'name' => 'Accessories'
            ],
            [
                'id' => 5,
                'name' => 'Sticker Rubik',
                'parent_id' => 4,
            ],
            [
                'id' => 6,
                'name' => 'Blog',
            ],
            [
                'name' => 'Brands'
            ],
            [
                'name' => 'Rubik 2x2',
                'parent_id' => 1,
            ],
            [
                'name' => 'Rubik 3x3',
                'parent_id' => 1,
            ],
            [
                'name' => 'Rubik 4x4',
                'parent_id' => 1,
            ],
            [
                'name' => 'Rubik 5x5',
                'parent_id' => 1,
            ],
            [
                'name' => 'Rubik 6x6',
                'parent_id' => 1,
            ],
            [
                'name' => 'Rubik 7x7',
                'parent_id' => 1,
            ],
            [
                'name' => 'Rubik 8x8',
                'parent_id' => 2,
            ],
            [
                'name' => 'Rubik 9x9',
                'parent_id' => 2,
            ],
            [
                'name' => 'Rubik 10x10',
                'parent_id' => 2,
            ],
            [
                'name' => 'Rubik 11x11',
                'parent_id' => 2,
            ],
            [
                'name' => 'Rubik 12x12',
                'parent_id' => 2,
            ],
            [
                'name' => 'Rubik 13x13',
                'parent_id' => 2,
            ],
            [
                'name' => 'Rubik 14x14',
                'parent_id' => 2,
            ],
            [
                'name' => 'Rubik 15x15',
                'parent_id' => 2,
            ],
            [
                'name' => 'Rubik Square-1',
                'parent_id' => 1,
            ],
            [
                'name' => 'Rubik Skewb',
                'parent_id' => 1,
            ],
            [
                'name' => 'Rubik Bump Mirror',
                'parent_id' => 2,
            ],
            [
                'name' => '4-Sided Rubik\'s Cube Variants',
                'parent_id' => 3,
            ],
            [
                'name' => '6-Sided Rubik\'s Cube Variants',
                'parent_id' => 3,
            ],
            [
                'name' => 'Rubik Megaminx',
                'parent_id' => 3,
            ],
            [
                'name' => 'Shape-shifting Rubik\'s Cube Variants',
                'parent_id' => 3,
            ],
            [
                'name' => 'Silicone',
                'parent_id' => 4,
            ],
            [
                'name' => 'Rubik Timer',
                'parent_id' => 4,
            ],
            [
                'name' => 'Sticker Full Fitted',
                'parent_id' => 5,
            ],
            [
                'name' => 'Sticker Fitted',
                'parent_id' => 5,
            ],
            [
                'name' => 'Basic Rubik\'s Cube Guide',
                'parent_id' => 6,
            ],
            [
                'name' => 'Advanced Rubik\'s Cube Guide',
                'parent_id' => 6,
            ],
            [
                'name' => 'Review',
                'parent_id' => 6,
            ],
            [
                'name' => 'News',
                'parent_id' => 6,
            ]
        ];
        foreach ($categories as $category) {
            $modelCategory = new Categories();
            $modelCategory->name = $category['name'];
            $modelCategory->parent_id = $category['parent_id'] ?? null;
            $modelCategory->slug = strtolower(Str::slug($category['name'], '-'));
            $modelCategory->save();
        }
    }
}
