<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengembalian;
use App\Models\Borrowing;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index()
    {
        // Ambil semua pengembalian dengan relasi borrowing dan stockItem
        $pengembalians = Pengembalian::with('borrowing.stockItem')->latest()->get();

        // Ambil pinjaman yang sedang dipinjam (status dipinjam) untuk form pengembalian
        $borrowings = Borrowing::where('status', 'dipinjam')->with('stockItem')->get();

        return view('barangs.pengambilan', compact('pengembalians', 'borrowings'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'tanggal_pengembalian' => 'required|date_format:Y-m-d\TH:i',  // format datetime-local input
        ]);

        // Pastikan borrowing_id yang dikirim statusnya 'dipinjam' (agar tidak bisa submit pengembalian utk pinjaman lain)
        $borrowing = Borrowing::where('id', $validated['borrowing_id'])
                              ->where('status', 'dipinjam')
                              ->first();

        if (!$borrowing) {
            return redirect()->back()->withErrors(['borrowing_id' => 'Peminjaman tidak valid atau sudah dikembalikan'])->withInput();
        }

        // Buat record pengembalian
        Pengembalian::create([
            'borrowing_id' => $validated['borrowing_id'],
            'tanggal_pengembalian' => Carbon::parse($validated['tanggal_pengembalian']),
        ]);

        // Update status peminjaman menjadi dikembalikan
        $borrowing->update(['status' => 'dikembalikan']);

        return redirect()->route('pengembalian.index')->with('success', 'Data pengembalian berhasil disimpan.');
    }
}
