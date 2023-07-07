<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->status == 'paid') {
            $color = "#065F46";
            $background = "#D1FAE5";
        } else {
            $color = "#991B1B";
            $background = "#FEE2E2";
        }
        $invoice  = [
            'status' => ucfirst($this->status),
            'color' => $color,
            'background' => $background,
            'total' => $this->total(),
            'date' => $this->date()->toFormattedDateString(),
            'plan_name' => ' - ' ,
            'plan_period' => Carbon::parse($this->date('m'))->format('F Y'),
            'user_name' => $this->customer_name,
            'invoice_no' => $this->number,
        ];
        if ($this->status != 'draft') {
            $invoice = $invoice +  [
                'link' => route('invoices.show', ['invoice' => $this->id]),
                'download_link' => route('invoices.download', ['invoice' => $this->id])];
        }
        return $invoice;
    }
}
