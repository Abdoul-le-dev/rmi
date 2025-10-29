<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpiredSubscriptionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Sale::where('type', 'subscribe')
            ->where('expires_at', '<', now()->timestamp)
            ->whereHas('buyer', function ($query) {
                $query->where('status', 'active');
            })
            ->with('buyer')
            ->get(); // Retrieve all necessary fields through the `map` method
    }

    public function headings(): array
    {
        return [
            'ID',
            'Buyer ID',
            'Full Name',
            'Email',
            'Phone Number',
            'Expires At',
        ];
    }

    public function map($expiredSubscription): array
    {
        return [
            $expiredSubscription->id,
            $expiredSubscription->buyer->id,
            $expiredSubscription->buyer->full_name, // Full name of the buyer
            $expiredSubscription->buyer->email, // Email of the buyer
            $expiredSubscription->buyer->mobile, // Mobile number of the buyer
            $expiredSubscription->expires_at,
        ];
    }
}
