<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\StockItem;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    // Tampilkan form peminjaman beserta data barang dan peminjaman
    public function index()
    {
        $stockItems = StockItem::all();
        $borrowings = Borrowing::with('stockItem')->orderBy('created_at', 'desc')->get();

        return view('peminjaman', compact('stockItems', 'borrowings'));
    }

    // Simpan data peminjaman baru
    public function store(Request $request)
    {
        // Validasi input form
        $validated = $request->validate([
            'stock_item_id' => 'required|exists:stock_items,id',
            'jumlah' => 'required|integer|min:1',
            'peminjam' => 'required|string|max:255',
        ]);

        // Ambil stok barang yang dipilih
        $stock = StockItem::findOrFail($validated['stock_item_id']);

        // Cek ketersediaan stok
        if ($stock->quantity < $validated['jumlah']) {
            return back()->withErrors(['jumlah' => 'Stok tidak mencukupi'])->withInput();
        }

        // Kurangi stok barang
        $stock->quantity -= $validated['jumlah'];
        $stock->save();

        // Simpan data peminjaman ke database
        Borrowing::create([
            'stock_item_id' => $validated['stock_item_id'],
            'jumlah' => $validated['jumlah'],
            'borrower_name' => $validated['peminjam'],
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil disimpan.');
    }
}
