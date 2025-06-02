<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\StockItem;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $stockItems = StockItem::all();
        $borrowings = Borrowing::with('stockItem')->orderBy('created_at', 'desc')->get();

        return view('barangs.peminjaman', compact('stockItems', 'borrowings'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'stock_item_id' => 'required|exists:stock_items,id',
        'jumlah'         => 'required|integer|min:1',
        'peminjam'       => 'required|string|max:255',
        'alasan'         => 'required|string|max:1000',
        'borrow_date'    => 'required|date',
        'return_date'    => 'nullable|date|after_or_equal:borrow_date',
    ]);

    // Ambil data stok barang
    $stock = StockItem::findOrFail($validated['stock_item_id']);

    // Cek apakah stok mencukupi
    if ($stock->quantity < $validated['jumlah']) {
        return back()->withErrors(['jumlah' => 'Stok tidak mencukupi'])->withInput();
    }

    // Kurangi stok
    $stock->quantity -= $validated['jumlah'];
    $stock->save();

    // Simpan data peminjaman
    Borrowing::create([
        'stock_item_id'  => $validated['stock_item_id'],
        'jumlah'         => $validated['jumlah'],
        'borrower_name'  => $validated['peminjam'],
        'alasan'         => $validated['alasan'],
        'borrow_date'    => $validated['borrow_date'],
        'return_date'    => $validated['return_date'] ?? null,
        'status'         => 'menunggu', // default status
    ]);

    return redirect()->route('approval.index')->with('success', 'Peminjaman berhasil disimpan dan menunggu persetujuan.');
}

}
