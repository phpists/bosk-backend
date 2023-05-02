<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceCollection;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        return new InvoiceCollection(Invoice::all()->where('user_id', $request->user()->id));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'number' => 'required',
            'provider_name' => 'required',
            'provider_tax_id' => 'required',
            'provider_lines' => 'required',
            'issue_date' => 'required',
            'due_date' => 'required',
            'po_number' => 'required',
            'subtotal' => 'required',
            'tax' => 'required|nullable',
            'total' => 'required',
            'note' => 'required',
            'status' => '',
            'customer_id' => 'required|exists:customers,id',
            'invoice_items' => 'required|array'
        ]);

        if (!array_key_exists('status', $validatedData)) {
            $validatedData['status'] = 'draft';
        }

        $validatedData['user_id'] = $request->user()->id;

        $invoice_items = $validatedData['invoice_items'];

        unset($validatedData['invoice_items']);

        $invoice = new Invoice($validatedData);

        $invoice->save();

        foreach ($invoice_items as $index=>$invoice_item) {
            $invoice_items[$index]['invoice_id'] = $invoice->id;
        }

        DB::table('invoice_items')->insert($invoice_items);

        return response()->json(null, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $invoice_id)
    {
        $invoice = Invoice::where('user_id', $request->user()->id)->find($invoice_id);
        if ($invoice === null) {
            return response()->json(null, 404);
        }

        return new InvoiceResource($invoice);
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
            'provider_tax_id' => '',
            'provider_lines' => '',
            'issue_date' => '',
            'due_date' => '',
            'po_number' => '',
            'subtotal' => '',
            'tax' => 'nullable',
            'total' => '',
            'note' => '',
            'customer_id' => 'exists:customers,id'
        ]);

        $invoice->update($validatedData);

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $invoice_id)
    {
        $invoice = Invoice::all()->where('user_id', $request->user()->id)->find($invoice_id);
        if ($invoice === null) {
            return response()->json(null, 404);
        }
        $invoice->delete();
        return response()->json(null, 201);
    }

    public function change_status(Request $request, int $invoice_id) {
        $invoice = Invoice::all()->where('user_id', $request->user()->id)->find($invoice_id);
        if ($invoice === null) {
            return response()->json(null, 404);
        }
        $status = $request->query('status', null);
        if ($status !== 'null') {
            $invoice->update(['status' => $status]);
        }
        return response()->json(null, 200);
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
}
