<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transactions\InvoiceRequest;
use App\Http\Resources\InvoiceDetailsResource;
use App\Http\Resources\InvoicesResource;
use App\Models\Campaigns\Campaigns;
use App\Models\Catalogs\Products;
use App\Models\Transactions\InvoiceDetails;
use App\Models\Transactions\Invoices;
use App\Models\Users\Customers;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function back;
use function json_decode;
use function redirect;
use function request;
use function view;

class InvoicesController extends Controller
{
    public const PAGE_LIMIT = 20;
    private Customers $customers;
    private Invoices $invoices;
    private InvoiceDetails $invoiceDetails;
    private Products $products;
    private Campaigns $campaigns;

    public function __construct(
        Customers      $customer,
        Invoices       $invoice,
        InvoiceDetails $invoiceDetail,
        Products       $products,
        Campaigns      $campaigns
    )
    {
        $this->customers = $customer;
        $this->invoices = $invoice;
        $this->invoiceDetails = $invoiceDetail;
        $this->products = $products;
        $this->campaigns = $campaigns;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $invoices = $this->invoices->paginate(self::PAGE_LIMIT);
        return view('transactions.invoices.list', [
            'invoices' => InvoicesResource::collection($invoices)->toArray(request()),
            'link' => $invoices->links(),
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
        $customers = $this->customers
            ->join('users', 'customers.user_id', '=', 'users.id')
            ->select('customers.id as customer_id', 'users.first_name', 'users.last_name', 'customers.identity_number')
            ->get()
            ->map(function ($customer) {
                $customer->full_info = $customer->first_name . ' ' . $customer->last_name . ' - ' . $customer->identity_number;
                return $customer;
            })
            ->pluck('full_info', 'customer_id');
        $products = $this->products->select('id', 'name', 'sku', 'price')->get()->toArray();
        $date = Carbon::now()->format('Y-m-d');
        $campaigns = $this->campaigns
            ->select('id', 'name')
            ->where('status', $this->campaigns::STATUS_ACTIVE)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->pluck('name', 'id');
        return view('transactions.invoices.create', [
            'current_employee' => [
                'full_name' => Auth::user()->full_name,
                'id' => Auth::user()->id,
            ],
            'campaigns' => $campaigns,
            'customers' => $customers,
            'products' => $products,
            'statuses' => $this->invoices->getStatusOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(InvoiceRequest $request)
    {
        $products = json_decode($request->input('products'), true);
        $campaign = $this->campaigns
            ->select('id', 'name', 'type', 'type_value')
            ->find($request->input('campaign_id'));
        DB::beginTransaction();
        $invoice = new $this->invoices;
        try {
            $invoice->employee_id = $request->input('employee_id');
            $invoice->customer_id = $request->input('customer_id');
            $invoice->date = $request->input('date');
            $invoice->status = $request->input('status');
            $invoice->campaign = $campaign->toJson();
            $invoice->note = $request->input('note');
            $invoice->save();
            foreach ($products as $product) {
                $invoiceDetail = new $this->invoiceDetails;
                $invoiceDetail->invoice_id = $invoice->id;
                $invoiceDetail->product_id = $product['id'];
                $invoiceDetail->quantity = $product['quantity'];
                $invoiceDetail->price = $product['price'];
                $invoiceDetail->save();
            }
            DB::commit();
            if ($request->input('action') === 'save_and_close') {
                return redirect()->route('invoices')->with('success', 'Created Successfully!');
            }
            return back()->with('success', 'Created Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function preview($id)
    {
        $invoice = new InvoicesResource($this->invoices->find($id));
        $invoiceDetails = $invoice->details()->get();
        return view('transactions.invoices.preview', [
            'invoice' => $invoice->toArray(request()),
            'invoiceDetails' => InvoiceDetailsResource::collection($invoiceDetails)->toArray(request()),
        ]);
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
