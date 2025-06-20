<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Loan::with(['user', 'item'])->get()->map(function ($loan) {
            return [
                'ID' => $loan->id,
                'User' => $loan->user->name,
                'Item' => $loan->item->name,
                'Quantity' => $loan->quantity,
                'Borrowed At' => $loan->borrowed_at,
                'Returned At' => $loan->returned_at,
                'Status' => $loan->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Item',
            'Quantity',
            'Borrowed At',
            'Returned At',
            'Status',
        ];
    }
}
