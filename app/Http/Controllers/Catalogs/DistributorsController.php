<?php

namespace App\Http\Controllers\Catalogs;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogs\DistributorRequest;
use App\Models\Catalog\Categories;
use App\Models\Catalog\Distributors;
use App\Schema\BrandSchema;
use App\Schema\CategorySchema;
use App\Schema\DistributorSchema;
use Illuminate\Http\Request;
use function back;
use function redirect;
use function view;

class DistributorsController extends Controller
{
    private Distributors $distributors;

    public function __construct(Distributors $distributor)
    {
        $this->distributors = $distributor;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $distributors = $this->distributors->paginate(13);
        foreach ($distributors as $key => $distributor) {
            $distributorSchema = new DistributorSchema($distributor);
            $distributors[$key] = $distributorSchema->convertData();
        }
        return view('catalogs.distributors.list', [
            'distributors' => $distributors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogs.distributors.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        $distributors = $this->distributors->find($id);
        $distributorSchema = new DistributorSchema($distributors);
        return view('catalogs.distributors.edit', [
            'distributors' => $distributorSchema->convertData(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
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
