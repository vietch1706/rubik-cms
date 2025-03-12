<?php

namespace App\Http\Controllers\Blogs;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Blogs\BlogRequest;
use App\Http\Resources\BlogsResource;
use App\Models\Blogs\Blogs;
use App\Models\Catalogs\Categories;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function request;
use function response;
use function view;

class BlogsController extends Controller
{
    public const PAGE_LIMIT = 20;
    private array $__includedCategories = [
        Categories::SLUG_BLOG,
    ];

    private Blogs $__blogs;
    private Categories $__categories;

    public function __construct(
        Blogs      $blog,
        Categories $category,
    )
    {
        $this->__blogs = $blog;
        $this->__categories = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $blogs = $this->__blogs->paginate(self::PAGE_LIMIT);
        return view('blogs.blogs.list', [
            'blogs' => BlogsResource::collection($blogs)->toArray(request()),
            'link' => $blogs->links(),
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
        return view('blogs.blogs.create', [
            'current_employee' => [
                'full_name' => Auth::user()->full_name,
                'id' => Auth::user()->id,
            ],
            'categories' => $this->__getIncludedCategories(),
        ]);
    }

    private function __getIncludedCategories()
    {
        $blogCategoryId = $this->__categories->where('slug', $this->__includedCategories[0])->value('id');
        return $this->__categories
            ->whereIn('parent_id', [$blogCategoryId])
            ->pluck('name', 'id');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(BlogRequest $request)
    {
        //
        $blog = new $this->__blogs;
        DB::beginTransaction();
        try {
            $blog->employee_id = $request->input('employee_id');
            $blog->category_id = $request->input('category_id');
            $blog->title = $request->input('title');
            $blog->slug = $request->input('slug');
            $blog->content = $request->input('content');
            $blog->date = $request->input('date');
            $blog->thumbnail = Helper::setStoragePath('thumbnails', $request->file('thumbnail'));
            $blog->save();
            DB::commit();
            if ($request->input('action') == 'save_and_close') {
                return redirect()->route('blogs')->with('success', 'Create blog successfully');
            }
            return back()->with('success', 'Create blog successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
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
        $blog = new BlogsResource($this->__blogs->find($id));
        return view('blogs.blogs.edit', [
            'blog' => $blog->toArray(request()),
            'categories' => $this->__getIncludedCategories(),
        ]);
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
    public function destroy(Request $request)
    {
        //
        $ids = $request->ids;
        $this->__blogs->whereIn('id', $ids)->delete();
        return response()->json([
                "success" => 'Blogs have been deleted'
            ]
        );
    }
}
