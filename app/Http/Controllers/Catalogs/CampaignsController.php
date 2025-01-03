<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Campaigns\CampaignDetails;
use App\Models\Catalogs\Campaigns\Campaigns;
use App\Schema\BrandSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function compact;
use function response;
use function view;

class CampaignsController extends Controller
{
    private Campaigns $campaigns;
    private CampaignDetails $campaignDetails;

    public function __construct(
        Campaigns       $campaign,
        CampaignDetails $campaignDetail,
    )
    {
        $this->campaigns = $campaign;
        $this->campaignDetails = $campaignDetail;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        return view('catalogs.campaigns.list', [
            'types' => $this->campaignDetails->getTypeOptions(),
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
        return view('catalogs.campaigns.create');
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
    public function destroy(Request $request)
    {
        $ids = $request->ids;
        $this->campaigns->whereIn('id', $ids)->delete();
        return response()->json(
            ["success" => 'Camapaigns have been deleted']
        );
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $campaigns = $this->campaigns
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('slug', 'like', '%' . $search . '%')
            ->paginate(self::PAGE_LIMIT);
        foreach ($campaigns as $key => $brand) {
            $brandSchema = new BrandSchema($brand);
            $campaigns[$key] = $brandSchema->convertData();
        }
        if ($campaigns->count() > 0) {
            return response()->json([
                'campaigns' => view('catalogs.campaigns.search', compact('campaigns'))->render(),
                'pagination' => $campaigns->links()->render(),
            ]);
        } else {
            return response()->json([
                'error' => 'No result found!',
            ]);
        }
    }
}
