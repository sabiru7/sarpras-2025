<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function index()
    {
        $stockItems = StockItem::all();
        $borrowings = Borrowing::with('stockItem')->latest()->get();
        return view('barangs.peminjaman', compact('stockItems', 'borrowings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stock_item_id' => 'required|exists:stock_items,id',
            'jumlah'        => 'required|integer|min:1',
            'peminjam'      => 'required|string|max:255',
            'alasan'        => 'required|string|max:1000',
            'borrow_date'   => 'required|date_format:Y-m-d\TH:i',
            'return_date'   => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:borrow_date',
        ]);

        $stock = StockItem::findOrFail($validated['stock_item_id']);
        if ($stock->quantity < $validated['jumlah']) {
            return back()->withErrors(['jumlah' => 'Stok tidak mencukupi'])->withInput();
        }

        $borrowDate = Carbon::createFromFormat('Y-m-d\TH:i', $validated['borrow_date']);
        $returnDate = $validated['return_date']
            ? Carbon::createFromFormat('Y-m-d\TH:i', $validated['return_date'])
            : null;

        Borrowing::create([
            'stock_item_id' => $validated['stock_item_id'],
            'jumlah'        => $validated['jumlah'],
            'borrower_name' => $validated['peminjam'],
            'alasan'        => $validated['alasan'],
            'borrow_date'   => $borrowDate,
            'return_date'   => $returnDate,
            'status'        => 'menunggu',
        ]);

        $stock->decrement('quantity', $validated['jumlah']);

        return redirect()->route('approval.index')->with('success', 'Peminjaman berhasil disimpan dan menunggu persetujuan.');
    }
}
