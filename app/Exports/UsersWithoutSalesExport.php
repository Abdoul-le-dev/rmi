<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersWithoutSalesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('role_name', 'user')
            ->doesntHave('buyer_sales')
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
