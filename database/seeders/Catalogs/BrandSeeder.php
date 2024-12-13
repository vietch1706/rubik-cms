<?php

namespace Database\Seeders\Catalogs;

use App\Models\Catalog\Brands;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use function strtolower;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $brands = [
            [
                'name' => 'Calvin Puzzles',
            ],
            [
                'name' => 'Cube4You'
            ],
            [
                'name' => 'Cubicle (USA)'
            ],
            [
                'name' => 'Cyclone Boy'
            ],
            [
                'name' => 'DaYan'
            ],
            [
                'name' => 'Mo Fang Ge'
            ],
            [
                'name' => 'FangShi'
            ],
            [
                'name' => 'GAN'
            ],
            [
                'name' => 'Hello Cube'
            ],
            [
                'name' => 'Kung Fu'
            ],
            [
                'name' => 'LanLan'
            ],
            [
                'name' => 'Lefun'
            ],
            [
                'name' => 'LingAo'
            ],
            [
                'name' => 'Maru'
            ],
            [
                'name' => 'Meffert\'s'
            ],
            [
                'name' => 'MF8'
            ],
            [
                'name' => 'Moretry Tianma'
            ],
            [
                'name' => 'MoYu'
            ],
            [
                'name' => 'MS Cube'
            ],
            [
                'name' => 'QiYi'
            ],
            [
                'name' => 'Rubik\'s'
            ],
            [
                'name' => 'V-Cube'
            ],
            [
                'name' => 'Very Puzzle'
            ],
            [
                'name' => 'Witeden'
            ],
            [
                'name' => 'XiaoMi'
            ],
            [
                'name' => 'YJ (YongJun)'
            ],
            [
                'name' => 'Yuxin'
            ],
            [
                'name' => 'Zii Cube'
            ]
        ];
        foreach ($brands as $brand) {
            $modelBrand = new Brands();
            $modelBrand->name = $brand['name'];
            $modelBrand->slug = strtolower(Str::slug($brand['name'], '-'));
            $modelBrand->image = '/storage/brands/' . $modelBrand->slug . '.png';
            $modelBrand->save();
        }
    }
}
