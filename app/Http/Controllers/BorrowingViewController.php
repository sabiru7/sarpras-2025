<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\StockItem;

class BorrowingViewController extends Controller
{
   public function index()
    {
        return view('barangs.peminjaman', [
            'stockItems' => StockItem::all(),
            'borrowings' => Borrowing::with('stockItem')->latest()->get(),
        ]);
    }
}
