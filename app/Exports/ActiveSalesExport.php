<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActiveSalesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Get the collection of active sales.
     */
    public function collection()
    {
        return Sale::where('type', Sale::$subscribe)
            ->where('expires_at', '>', time())
            ->whereNull('refund_at')
            ->whereHas('buyer', function ($query) {
                $query->where('status', 'active');
            })
            ->with(['buyer', 'seller', 'subscribe'])
            ->get();
    }

    /**
     * Define the headings for the export.
     */
    public function headings(): array
    {
        return [
            'Sale ID',
            'Buyer Name',
            'Buyer Email',
            'Buyer Phone',
            'Seller Name',
            'Seller Email',
            'Subscription Plan',
            'Amount',
            'Expires At',
            'Created At',
        ];
    }

    /**
     * Map each sale to a row in the export.
     */
    public function map($sale): array
    {
        return [
            $sale->id,
            $sale->buyer->full_name ?? 'N/A',
            $sale->buyer->email ?? 'N/A',
            $sale->buyer->mobile ?? 'N/A',
            $sale->seller->full_name ?? 'N/A',
            $sale->seller->email ?? 'N/A',
            $sale->subscribe->title ?? 'N/A',
            $sale->amount ?? 0,
            !empty($sale->expires_at) ? \Carbon\Carbon::createFromTimestamp($sale->expires_at)->format('Y-m-d') : 'N/A',
            \Carbon\Carbon::createFromTimestamp($sale->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
