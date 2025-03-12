<?php

namespace App\Http\Controllers\Campaigns;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaigns\CampaignRequest;
use App\Http\Resources\BrandsResource;
use App\Http\Resources\CampaignsResource;
use App\Models\Campaigns\Campaigns;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use function back;
use function redirect;
use function request;
use function response;
use function view;

class CampaignsController extends Controller
{
    public const PAGE_LIMIT = 20;
    private Campaigns $campaigns;

    public function __construct(
        Campaigns $campaign,
    )
    {
        $this->campaigns = $campaign;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $campaigns = $this->campaigns->paginate(self::PAGE_LIMIT);
        return view('campaigns.campaigns.list', [
            'campaigns' => CampaignsResource::collection($campaigns)->toArray(request()),
            'link' => $campaigns->links(),
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
        return view('campaigns.campaigns.create', [
            'types' => $this->campaigns->getTypeOptions(),
            'statuses' => $this->campaigns->getStatusOptions()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(CampaignRequest $request)
    {
        //
        $campaign = new $this->campaigns;
        DB::beginTransaction();
        try {
            $campaign->name = $request->input('name');
            $campaign->slug = $request->input('slug');
            $campaign->status = $request->input('status');
            $campaign->type = $request->input('type');
            $campaign->start_date = $request->input('start_date');
            $campaign->end_date = $request->input('end_date');
            if ($request->input('discount_value')) {
                $campaign->type_value = $request->input('discount_value');
            } else {
                $campaign->type_value = $request->input('bundle_value');
            }
            $campaign->save();
            DB::commit();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('campaigns')->with('success', 'Created Successfully!');
            }
            return back()->with('success', 'Created Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(['error' => $e->getMessage()]);
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
        $campaign = new CampaignsResource($this->campaigns->find($id));
        return view('campaigns.campaigns.edit', [
            'campaign' => $campaign->toArray(request()),
            'types' => $this->campaigns->getTypeOptions(),
            'statuses' => $this->campaigns->getStatusOptions(),
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
        $ids = $request->ids;
        $this->campaigns->whereIn('id', $ids)->delete();
        return response()->json([
                "success" => 'Camapaigns have been deleted'
            ]
        );
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $campaigns = $this->campaigns
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('slug', 'like', '%' . $search . '%')
            ->paginate(self::PAGE_LIMIT);
        if ($campaigns->count() > 0) {
            return response()->json([
                'campaigns' => view('campaigns.campaigns.search', [
                    'campaigns' => BrandsResource::collection($campaigns)->toArray(request()),
                ])->render(),
                'pagination' => $campaigns->links()->render()
            ]);
        } else {
            return response()->json([
                'error' => 'No result found!',
            ]);
        }
    }
}
