<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with('stockItem')->where('status', 'menunggu')->get();
        return view('laporan.approval', compact('borrowings'));
    }

public function approve($id)
{
    $borrowing = Borrowing::findOrFail($id);
    if ($borrowing->status === 'menunggu') {
        // Ubah status langsung ke dipinjam setelah disetujui
        $borrowing->status = 'dipinjam';
        $borrowing->save();

        return redirect()->route('approval.index')->with('success', 'Peminjaman disetujui dan status diubah menjadi dipinjam.');
    }
    return redirect()->route('approval.index')->with('danger', 'Status peminjaman tidak dapat diubah.');
}

    public function reject($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        if ($borrowing->status === 'menunggu') {
            // Kalau mau stok dikembalikan saat reject, tambahkan logika stok di sini
            $stock = $borrowing->stockItem;
            $stock->quantity += $borrowing->jumlah;
            $stock->save();

            $borrowing->status = 'ditolak';
            $borrowing->save();

            return redirect()->route('approval.index')->with('success', 'Peminjaman ditolak dan stok dikembalikan.');
        }
        return redirect()->route('approval.index')->with('danger', 'Status peminjaman tidak dapat diubah.');
    }
}
