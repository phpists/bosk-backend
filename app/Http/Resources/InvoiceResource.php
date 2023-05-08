<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'number' => $this->number,
            'provider_name' => $this->provider_name,
            'provider_tax_id' => $this->provider_tax_id,
            'provider_lines' => $this->provider_lines,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'po_number' => $this->po_number,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total,
            'note' => $this->note,
            'customer_id' => $this->customer_id,
            'invoice_items_attributes' => InvoiceItemResource::collection($this->invoice_items),
            'status' => $this->status
        ];
    }
}
