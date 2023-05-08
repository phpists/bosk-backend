<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class InvoicePdfController extends Controller
{
    public function invoice_pdf(Request $request, int $id) {
        $invoice = Invoice::all()->find($id);
        if ($invoice === null) {
            return response()->json([
                'status' => false,
                'message' => 'Not found'
            ], 404);
        }
        $html = view('invoice', [
            'invoice' => $invoice
        ]);
        $mpdf = new Mpdf();

        // Generate PDF from HTML
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        exit;
    }
}
