<?php

namespace App\Http\Controllers\Catalogs;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogs\BrandRequest;
use App\Http\Resources\BrandsResource;
use App\Models\Catalogs\Brands;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function back;
use function redirect;
use function request;
use function response;
use function view;

class BrandsController extends Controller
{
    public const PAGE_LIMIT = 20;
    private Brands $brands;

    public function __construct(Brands $brand)
    {
        $this->brands = $brand;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $brands = $this->brands->paginate(self::PAGE_LIMIT);
        return view('catalogs.brands.list', [
            'brands' => BrandsResource::collection($brands)->toArray(request()),
            'link' => $brands->links(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('catalogs.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
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
        } catch (Exception $e) {
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
        $brand = new BrandsResource($this->brands->find($id));
        return view('catalogs.brands.edit', [
            'brand' => $brand->toArray(request()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
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
    public function destroy(Request $request)
    {
        //
        $ids = $request->ids;
        $this->brands->whereIn('id', $ids)->delete();
        return response()->json([
                "success" => 'Brands have been deleted'
            ]
        );
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $brands = $this->brands
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('slug', 'like', '%' . $search . '%')
            ->paginate(self::PAGE_LIMIT);
        if ($brands->count() > 0) {
            return response()->json([
                'brands' => view('catalogs.brands.search', [
                    'brands' => BrandsResource::collection($brands)->toArray(request()),
                ])->render(),
                'pagination' => $brands->links()->render()
            ]);
        } else {
            return response()->json([
                'error' => 'No result found!',
            ]);
        }
    }
}
