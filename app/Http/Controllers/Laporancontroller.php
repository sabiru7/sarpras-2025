<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        // Ambil data lengkap dengan relasi stockItem
        $borrowings = Borrowing::with('stockItem')->latest()->get();

        // Total barang dipinjam (jumlah field)
        $totalDipinjam = $borrowings->sum('jumlah');

        // Peminjam unik berdasarkan borrower_name
        $peminjamUnik = $borrowings->pluck('borrower_name')->filter()->unique()->count();

        // Filter status kembali
        $pengembalian = $borrowings->where('status', 'kembali');

        // Hitung kembali tepat waktu
        $kembaliTepatWaktu = $pengembalian->filter(function ($item) {
            if (!$item->return_date || !$item->actual_return_date) {
                return false;
            }
            return Carbon::parse($item->actual_return_date)->lte(Carbon::parse($item->return_date));
        })->count();

        $totalPengembalian = $pengembalian->count();

        $persenTepat = $totalPengembalian > 0
            ? round(($kembaliTepatWaktu / $totalPengembalian) * 100, 2)
            : 0;

        // Tanggal laporan sekarang
        $tanggalLaporan = Carbon::now();

        return view('laporan.laporan', compact(
            'borrowings',
            'totalDipinjam',
            'peminjamUnik',
            'persenTepat',
            'tanggalLaporan'
        ));
    }
}
