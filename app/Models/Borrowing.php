<?php


// app/Models/Borrowing.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = ['stock_item_id', 'jumlah', 'borrower_name'];

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}
