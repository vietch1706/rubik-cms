<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Distributors;
use App\Models\Transactions\ImportReceiptDetails;
use App\Models\Transactions\ImportReceipts;
use App\Schema\ImportReceiptDetailSchema;
use App\Schema\ImportReceiptSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Csv\Reader;
use function fgetcsv;
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
            $importReceiptSchema = new ImportReceiptSchema($importReceipt);
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


    public function import(Request $request)
    {
        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), "r");
        $headers = fgetcsv($handle);
        $data = fgetcsv($handle);
        dd($data);
        while (($data = fgetcsv($handle)) !== FALSE) {
            // Combine headers with data
            $row = array_combine($headers, $data);
        }
        dd($row);
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

    public function preview($id)
    {
        //
        $importReceipts = $this->importReceipts->find($id);
        $importReceiptSchema = new ImportReceiptSchema($importReceipts);
        $importReceiptDetails = $importReceipts->details()->get();
        foreach ($importReceiptDetails as $key => $importReceiptDetail) {
            $importReceiptDetailSchema = new ImportReceiptDetailSchema($importReceiptDetail);
            $importReceiptDetails[$key] = $importReceiptDetailSchema->convertData();
        }
        return view('transactions.import_receipts.preview', [
            'importReceipts' => $importReceiptSchema->convertData(),
            'importReceiptDetails' => $importReceiptDetails,
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
    public function destroy($id)
    {
        //
    }
}
