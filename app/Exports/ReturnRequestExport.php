<?php

namespace App\Exports;

use App\Models\ReturnRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReturnRequestExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ReturnRequest::with(['loan.user', 'loan.item'])->get()->map(function ($request) {
            return [
                'ID' => $request->id,
                'User' => optional($request->loan->user)->name,
                'Item' => optional($request->loan->item)->name,
                'Return Date' => $request->return_date,
                'Quantity' => $request->quantity,
                'Status' => $request->status,
                'Photo' => $request->photo ?? '-',
                'Requested At' => $request->created_at->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Item',
            'Return Date',
            'Quantity',
            'Status',
            'Photo',
            'Requested At',
        ];
    }
}
