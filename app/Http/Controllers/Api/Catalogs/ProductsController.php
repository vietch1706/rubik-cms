<?php

namespace App\Http\Controllers\Api\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductsResource;
use App\Models\Catalogs\Products;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    private Products $products;

    public function __construct(
        Products $product,
    )
    {
        $this->products = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
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
    public function show(Request $request)
    {
        //
        $param = $request->all();
        $result = null;
        if (!empty($param['slug'])) {
            $result = $this->products->getBySlug($param['slug'])->get();
        } elseif (!empty($param['id'])) {
            $result = $this->products->getById($param['id'])->get();
        } elseif (!empty($param['category'])) {
            $result = $this->products->getByCategoryId($param['category'])->get();
        } elseif (!empty($param['brand'])) {
            $result = $this->products->getByBrandId($param['brand'])->get();
        } elseif (!empty($param['distributor'])) {
            $result = $this->products->getByDistributorId($param['distributor'])->get();
        } else {
            $result = $this->products->all();
        }
        if ($result->isEmpty()) {
            return [
                'message' => 'empty'
            ];
        }
        return ProductsResource::collection($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public
    function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public
    function destroy($id)
    {
        //
    }
}
