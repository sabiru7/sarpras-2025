<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
    
{
    public function index()
    {
        // Hardcoded data
        $barangs = [
            [
                'nama' => 'Proyektor',
                'stok' => 2,
                'gambar' => 'https://images.unsplash.com/photo-1603791440384-56cd371ee9a7?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama' => 'Spidol',
                'stok' => 5,
                'gambar' => 'https://images.unsplash.com/photo-1589927986089-358c8c8c8c8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama' => 'Kabel HDMI',
                'stok' => 4,
                'gambar' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama' => 'Laptop',
                'stok' => 3,
                'gambar' => 'https://images.unsplash.com/photo-1511988617509-a57c8a288659?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama' => 'Penghapus',
                'stok' => 10,
                'gambar' => 'https://images.unsplash.com/photo-1529472119199-5a99a4c1d11f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama' => 'Isi Ulang Spidol',
                'stok' => 6,
                'gambar' => 'https://images.unsplash.com/photo-1602773862742-3f3e1f1f86da?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
        ];

        return view('dashboard', compact('barangs'));
    }



    // Borrow an item
 public function pinjam(Request $request)
{
    $request->validate([
        'nama' => 'required|string',
    ]);

    // Find the item by name
    $barang = Barang::where('nama', $request->nama)->first();

    if (!$barang) {
        return response()->json(['message' => 'Barang tidak ditemukan.'], 404);
    }

    if ($barang->stok <= 0) {
        return response()->json(['message' => 'Stok barang tidak tersedia.'], 400);
    }

    // Decrement the stock
    $barang->stok--;
    $barang->save();

    return response()->json(['message' => 'Anda berhasil meminjam ' . $barang->nama]);
}


}
