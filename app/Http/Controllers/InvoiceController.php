<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceCollection;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Mpdf\Mpdf;

class InvoiceController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $invoices = Invoice::all()->where('user_id', $request->user()->id);
        $collection = new InvoiceCollection($invoices);
        return response()->json($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => '',
            'number' => 'required',
            'provider_name' => '',
            'provider_tax_id' => '',
            'provider_lines' => '',
            'issue_date' => 'required',
            'due_date' => 'required',
            'po_number' => '',
            'subtotal' => '',
            'tax' => '',
            'total' => '',
            'note' => '',
            'status' => [Rule::in(['paid', 'unpaid', 'draft', 'canceled', 'overdue'])],
            'customer_id' => 'required|exists:customers,id',
            'invoice_items' => ''
        ]);

        if (!array_key_exists('status', $validatedData)) {
            $validatedData['status'] = 'draft';
        }

        $validatedData['user_id'] = $request->user()->id;

        if (array_key_exists('invoice_items', $validatedData)) {
            $invoice_items = $validatedData['invoice_items'];

            foreach ($invoice_items as $index=>$invoice_item) {
                $invoice_items[$index]['invoice_id'] = $invoice->id;
            }

            DB::table('invoice_items')->insert($invoice_items);

            unset($validatedData['invoice_items']);
        }

        $invoice = new Invoice($validatedData);

        $invoice->save();
        $resource = new InvoiceResource($invoice);
        return response()->json($resource);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $invoice_id)
    {
        $invoice = Invoice::where('user_id', $request->user()->id)->find($invoice_id);
        if ($invoice === null) {
            return response()->json([
                'status' => false,
                'message' => 'Not found'
            ], 404);
        }
        $resource = new InvoiceResource($invoice);
        return response()->json($resource);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $invoice_id)
    {
        $invoice = Invoice::all()->where('user_id', $request->user()->id)->find($invoice_id);
        if ($invoice === null) {
            return response()->json(null, 404);
        }
        $validatedData = $request->validate([
            'name' => '',
            'number' => '',
            'provider_name' => '',
            'provider_tax_id' => 'integer',
            'provider_lines' => '',
            'issue_date' => 'date',
            'due_date' => 'date',
            'po_number' => '',
            'subtotal' => 'numeric',
            'tax' => 'nullable|numeric',
            'total' => 'numeric',
            'note' => '',
            'customer_id' => 'exists:customers,id'
        ]);

        $invoice->update($validatedData);

        return response()->json($invoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $invoice_id)
    {
        $invoice = Invoice::all()->where('user_id', $request->user()->id)->find($invoice_id);
        if ($invoice === null) {
            return response()->json([
                'status' => false,
                'message' => 'Not found'
            ], 404);
        }
        $invoice->delete();
        return response()->json(null, 201);
    }

    public function change_status(Request $request, int $invoice_id) {
        $invoice = Invoice::all()->where('user_id', $request->user()->id)->find($invoice_id);
        if ($invoice === null) {
            return response()->json([
                'status' => false,
                'message' => 'Not found'
            ], 404);
        }
        $status = $request->query('status', null);
        if ($status === null) {
            return response()->json([
                'status' => false,
                'message' => "status parameter should be provide"
            ], 422);
        }
        if (in_array($status, ['draft', 'paid', 'unpaid', 'overdue', 'canceled'])) {
            $invoice->update(['status' => $status]);
        }
        else {
            return response()->json([
                'status' => false,
                'message' => "Status should be one of the following: draft, paid, unpaid, overdue, canceled"
            ], 422);
        }
        return response()->json($invoice, 200);
    }

    public function count_invoices(Request $request) {
        return response()->json(DB::table('invoices')->where('user_id', $request->user()->id)->count());
    }

    public function invoices_summary(Request $request){
        $summary = DB::table('invoices')->where('user_id', $request->user()->id);
        $total = $summary->sum('total');
        $overdue = $summary->whereIn('status', ['overdue', 'unpaid'])->sum('total');
        return response()->json(
            [
                'total' => $total,
                'overdue' => $overdue
            ]
        );
    }

    public function invoices_chart(Request $request){
        $summary = DB::table('invoices')
            ->selectRaw('DATE_FORMAT(issue_date, "%Y-%m") as dt, sum(total) as total')
            ->where('user_id', $request->user()->id)
            ->groupByRaw('dt')
            ->get()
            ->toArray();

        $new_summary = [];
        foreach ($summary as $stat) {
            $new_summary[$stat->dt] = $stat->total;
        }
        return response()->json($new_summary);
    }

    public function invoices_advice(Request $request) {
        $country_code = $request->query('country_code', null);
        if ($country_code === null) {
            return response()->json([
                'status' => false,
                'message' => 'Country code parameter should be provided'
            ], 422);
        }
        return response()->json(DB::table('customers')->select(['VAT', 'footer'])->first());
    }
}
