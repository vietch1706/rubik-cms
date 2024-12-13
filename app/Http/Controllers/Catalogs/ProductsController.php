<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Brands;
use App\Models\Catalog\Categories;
use App\Models\Catalog\Products;
use App\Schema\ProductSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function view;

class ProductsController extends Controller
{

    private Products $products;
    private Categories $categories;
    private Brands $brands;

    public function __construct(
        Products   $product,
        Categories $category,
        Brands     $brand,
    )
    {
        $this->products = $product;
        $this->categories = $category;
        $this->brands = $brand;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $products = $this->products->paginate(13);
        foreach ($products as $key => $product) {
            $productSchema = new ProductSchema($product);
            $products[$key] = $productSchema->convertData();
        }
//        dd($products);
        return view('catalogs.products.list', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $categories = $this->categories->pluck('name', 'id');
        $brands = $this->brands->pluck('name', 'id');
        return view('catalogs.products.create', [
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
