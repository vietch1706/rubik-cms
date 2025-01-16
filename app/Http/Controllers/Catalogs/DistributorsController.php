<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogs\DistributorRequest;
use App\Http\Resources\DistributorsResource;
use App\Models\Catalogs\Distributors;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function back;
use function redirect;
use function request;
use function response;
use function view;

class DistributorsController extends Controller
{
    public const PAGE_LIMIT = 20;
    private Distributors $distributors;

    public function __construct(Distributors $distributor)
    {
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
        $distributors = $this->distributors->paginate(self::PAGE_LIMIT);
        return view('catalogs.distributors.list', [
            'distributors' => DistributorsResource::collection($distributors)->toArray(request()),
            'link' => $distributors->links(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('catalogs.distributors.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(DistributorRequest $request)
    {
        //
        $distributor = new $this->distributors;
        try {
            $distributor->name = $request->input('name');
            $distributor->address = $request->input('address');
            $distributor->country = $request->input('country');
            $distributor->phone = $request->input('phone');
            $distributor->email = $request->input('email');
            $distributor->save();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('distributors')->with('success', 'Created Successfully!');
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
        $distributor = new DistributorsResource($this->distributors->find($id));
        return view('catalogs.distributors.edit', [
            'distributor' => $distributor->toArray(request()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(DistributorRequest $request, $id)
    {
        //
        $distributor = $this->distributors->find($id);
        try {
            $distributor->name = $request->input('name');
            $distributor->address = $request->input('address');
            $distributor->country = $request->input('country');
            $distributor->phone = $request->input('phone');
            $distributor->email = $request->input('email');
            $distributor->save();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('distributors')->with('success', 'Updated Successfully!');
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
        $this->distributors->whereIn('id', $ids)->delete();
        return response()->json([
                "success" => 'Categories have been deleted'
            ]
        );
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $distributors = $this->distributors
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('address', 'like', '%' . $search . '%')
            ->orWhere('country', 'like', '%' . $search . '%')
            ->orWhere('phone', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->paginate(self::PAGE_LIMIT);
        if ($distributors->count() > 0) {
            return response()->json([
                'distributors' => view('catalogs.distributors.search', [
                    'distributors' => DistributorsResource::collection($distributors)->toArray(request()),
                ])->render(),
                'pagination' => $distributors->links()->render()
            ]);
        } else {
            return response()->json([
                'error' => 'No result found!',
            ]);
        }
    }
}
