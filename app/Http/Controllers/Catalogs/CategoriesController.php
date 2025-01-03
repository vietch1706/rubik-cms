<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogs\CategoryRequest;
use App\Models\Catalogs\Categories;
use App\Schema\CategorySchema;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function back;
use function compact;
use function redirect;
use function response;
use function view;

class CategoriesController extends Controller
{
    public const PAGE_LIMIT = 20;
    private Categories $categories;

    public function __construct(Categories $category)
    {
        $this->categories = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $categories = $this->categories->paginate(self::PAGE_LIMIT);
        foreach ($categories as $key => $category) {
            $categorySchema = new CategorySchema($category);
            $categories[$key] = $categorySchema->convertData();
        }
        return view('catalogs.categories.list', [
            'categories' => $categories,
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
        return view('catalogs.categories.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(CategoryRequest $request)
    {
        //
        $category = new $this->categories;
        try {
            $category->parent_id = $request->input('parent_id');
            $category->name = $request->input('name');
            $category->slug = $request->input('slug');
            $category->save();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('categories')->with('success', 'Created Successfully!');
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
        $parentCategories = $this->categories->pluck('name', 'id');
        $category = $this->categories->find($id);
        $categorySchema = new CategorySchema($category);
        return view('catalogs.categories.edit', [
            'parentCategories' => $parentCategories,
            'category' => $categorySchema->convertData(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(CategoryRequest $request, $id)
    {
        //
        $category = $this->categories->find($id);
        try {
            $category->parent_id = $request->input('parent_id');
            $category->name = $request->input('name');
            $category->slug = $request->input('slug');
            $category->save();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('categories')->with('success', 'Updated Successfully!');
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
        $this->categories->whereIn('id', $ids)->delete();
        return response()->json(
            ["success" => 'Categories have been deleted']
        );
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $categories = $this->categories->whereHas('parentCategory', function ($query) use ($search) {
            return $query
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('slug', 'like', '%' . $search . '%');
        })
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('slug', 'like', '%' . $search . '%')
            ->paginate(self::PAGE_LIMIT);
        foreach ($categories as $key => $category) {
            $categorySchema = new CategorySchema($category);
            $categories[$key] = $categorySchema->convertData();
        }
        if ($categories->count() > 0) {
            return response()->json([
                'categories' => view('catalogs.categories.search', compact('categories'))->render(),
                'pagination' => $categories->links()->render(),
            ]);
        } else {
            return response()->json([
                'error' => 'No result found!',
            ]);
        }
    }
}
