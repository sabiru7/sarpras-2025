<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'stock_item_id',
        'jumlah',
        'borrower_name',
        'alasan',
        'borrow_date',
        'return_date',
        'status',
    ];

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}
