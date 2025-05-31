<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'stock_item_id',
        'jumlah',
        'borrower_name',
        'alasan',   // Tambahan kolom alasan
    ];

    // Relasi ke StockItem
    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}
