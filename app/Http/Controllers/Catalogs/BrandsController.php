<?php

namespace App\Http\Controllers\Catalogs;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogs\BrandRequest;
use App\Models\Catalog\Brands;
use App\Schema\BrandSchema;
use App\Schema\CustomerSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function back;
use function redirect;
use function view;

class BrandsController extends Controller
{
    private Brands $brands;

    public function __construct(Brands $brand)
    {
        $this->brands = $brand;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $brands = $this->brands->paginate(13);
        foreach ($brands as $key => $brand) {
            $brandSchema = new BrandSchema($brand);
            $brands[$key] = $brandSchema->convertData();
        }
        return view('catalogs.brands.list', [
            'brands' => $brands,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogs.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandRequest $request)
    {
        $brand = new $this->brands;
        try {
            $brand->name = $request->input('name');
            $brand->slug = $request->input('slug');
            $brand->image = Helper::setStoragePath('img', $request->file('image'));
            $brand->save();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('brands')->with('success', 'Created Successfully!');
            }
            return back()->with('success', 'Created Successfully!');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $brands = $this->brands->find($id);
        $brandSchema = new BrandSchema($brands);
        return view('catalogs.brands.edit', [
            'brands' => $brandSchema->convertData(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandRequest $request, $id)
    {
        //
        $brand = $this->brands->find($id);
        try {
            $brand->name = $request->input('name');
            $brand->slug = $request->input('slug');
            $brand->image = Helper::setStoragePath('img', $request->file('image'));
            $brand->save();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('brands')->with('success', 'Updated Successfully!');
            }
            return back()->with('success', 'Updated Successfully!');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
