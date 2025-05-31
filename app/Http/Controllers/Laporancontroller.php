<?php
// app/Http/Controllers/LaporanController.php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with('stockItem')->latest()->get();

        // Tambahkan ringkasan
        $totalDipinjam = $borrowings->sum('jumlah');
        $peminjamUnik = $borrowings->pluck('peminjam')->unique()->count();
        $kembaliTepatWaktu = $borrowings->where('status', 'kembali_tepat')->count();
        $persenTepat = $borrowings->count() > 0
            ? round(($kembaliTepatWaktu / $borrowings->count()) * 100, 2)
            : 0;

        return view('barangs.laporan', compact(
            'borrowings',
            'totalDipinjam',
            'peminjamUnik',
            'persenTepat'
        ));
    }
}
