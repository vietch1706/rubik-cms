<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Distributors;
use App\Models\Transactions\ImportReceiptDetails;
use App\Models\Transactions\ImportReceipts;
use App\Schema\OrderSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function view;

class ImportReceiptsController extends Controller
{
    public const PAGE_LIMIT = 20;
    private ImportReceipts $importReceipts;
    private ImportReceiptDetails $importReceiptDetails;
    private Distributors $distributors;

    public function __construct(
        ImportReceipts       $importReceipt,
        ImportReceiptDetails $importReceiptDetail,
    )
    {
        $this->importReceipts = $importReceipt;
        $this->importReceiptDetails = $importReceiptDetail;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $importReceipts = $this->importReceipts
            ->paginate(self::PAGE_LIMIT);
        foreach ($importReceipts as $key => $importReceipt) {
            $importReceiptSchema = new OrderSchema($importReceipt);
            $importReceipts[$key] = $importReceiptSchema->convertData();
        }
        return view('transactions.import_receipts.list', [
            'importReceipts' => $importReceipts,
        ]);
    }

    public function importView()
    {
        return view('transactions.import_receipts.import');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
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
    public function destroy($id)
    {
        //
    }
}
