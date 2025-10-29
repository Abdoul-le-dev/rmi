<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersWithWebinarOnlyExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::whereHas('buyer_sales', function ($query) {
            $query->where('type', 'webinar');
        })
            ->whereDoesntHave('buyer_sales', function ($query) {
                $query->where('type', 'subscribe');
            })
            ->get(['id', 'full_name', 'email', 'mobile']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Full Name',
            'Email',
            'Mobile',
        ];
    }
}
