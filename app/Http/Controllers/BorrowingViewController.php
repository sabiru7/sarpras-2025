<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\StockItem;
use Illuminate\Http\Request;

class BorrowingViewController extends Controller
{
    /**
     * Tampilkan halaman peminjaman barang
     */
    public function index()
    {
        $borrowings = Borrowing::with('stockItem')->orderBy('created_at', 'desc')->get();
        $stockItems = StockItem::all();

        return view('barangs.peminjaman', compact('borrowings', 'stockItems'));
    }
}
