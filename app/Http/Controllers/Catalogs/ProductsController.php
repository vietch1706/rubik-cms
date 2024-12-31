<?php

namespace App\Http\Controllers\Catalogs;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogs\ProductRequest;
use App\Models\Catalogs\Brands;
use App\Models\Catalogs\Categories;
use App\Models\Catalogs\Distributors;
use App\Models\Catalogs\Products;
use App\Schema\ProductSchema;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use function back;
use function redirect;
use function view;

class ProductsController extends Controller
{

    public const PAGE_LIMIT = 20;
    private Products $products;
    private Categories $categories;
    private Brands $brands;
    private Distributors $distributors;

    public function __construct(
        Products     $product,
        Categories   $category,
        Brands       $brand,
        Distributors $distributor
    )
    {
        $this->products = $product;
        $this->categories = $category;
        $this->brands = $brand;
        $this->distributors = $distributor;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $products = $this->products->paginate(self::PAGE_LIMIT);
        foreach ($products as $key => $product) {
            $productSchema = new ProductSchema($product);
            $products[$key] = $productSchema->convertData();
        }
        return view('catalogs.products.list', [
            'products' => $products,
            'magnetics' => $this->products->getMagneticOptions(),
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
        $distributors = $this->distributors->pluck('name', 'id');
        return view('catalogs.products.create', [
            'categories' => $categories,
            'brands' => $brands,
            'distributors' => $distributors,
            'magnetics' => $this->products->getMagneticOptions(),
            'statuses' => $this->products->getStatusOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(ProductRequest $request)
    {
        //
        DB::beginTransaction();
        try {
            $product = new $this->products;
            $product->name = $request->input('name');
            $product->category_id = $request->input('category_id');
            $product->brand_id = $request->input('brand_id');
            $product->slug = $request->input('slug');
            $product->sku = $request->input('sku');
            $product->release_date = $request->input('release_date');
            $product->weight = $request->input('weight');
            $product->box_weight = $request->input('box_weight');
            $product->magnetic = $request->input('magnetic');
            $product->quantity = $request->input('quantity');
            $product->image = Helper::setStoragePath('img', $request->file('image'));
            $product->save();
            $distributor = $this->distributors->find($request->input('distributor_id'));
            $distributor->products()->attach([$product->id]);
            DB::commit();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('products')->with('success', 'Created Successfully!');
            }
            return back()->with('success', 'Created Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
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
        $categories = $this->categories->pluck('name', 'id');
        $brands = $this->brands->pluck('name', 'id');
        $distributors = $this->distributors->pluck('name', 'id');
        $product = $this->products->find($id);
        $productSchema = new ProductSchema($product);
        return view('catalogs.products.edit', [
            'product' => $productSchema->convertData(),
            'categories' => $categories,
            'brands' => $brands,
            'distributors' => $distributors,
            'magnetics' => $this->products->getMagneticOptions(),
            'statuses' => $this->products->getStatusOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ProductRequest $request, $id)
    {
        //
        $product = $this->products->find($id);
        try {
            $product->name = $request->input('name');
            $product->category_id = $request->input('category_id');
            $product->brand_id = $request->input('brand_id');
            $product->slug = $request->input('slug');
            $product->sku = $request->input('sku');
            $product->release_date = $request->input('release_date');
            $product->weight = $request->input('weight');
            $product->box_weight = $request->input('box_weight');
            $product->magnetic = $request->input('magnetic');
            $product->image = Helper::setStoragePath('img', $request->file('image'));
            $product->quantity = $request->input('quantity');
            $product->save();
            $distributor = $this->distributors->find($request->input('distributor_id'));
            $distributor->products()->sync([$product->id]);
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('products')->with('success', 'Updated Successfully!');
            }
            return back()->with('success', 'Updated Successfully!');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
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
