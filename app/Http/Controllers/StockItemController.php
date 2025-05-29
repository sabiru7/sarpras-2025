<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StockItemController extends Controller
{
    public function index()
    {
        return StockItem::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null;

        $item = StockItem::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'category' => $request->category,
            'photo' => $path ? Storage::url($path) : null, // Menggunakan Storage::url untuk mendapatkan URL yang benar
        ]);

        return response()->json($item, 201);
    }

    public function show($id)
    {
        return StockItem::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $item = StockItem::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Jika ada foto baru, simpan dan hapus foto lama jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($item->photo) {
                $oldPath = str_replace('/storage/', '', $item->photo); // Menghapus '/storage/' untuk mendapatkan path yang benar
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $item->photo = Storage::url($path); // Menggunakan Storage::url untuk mendapatkan URL yang benar
        }

        $item->update([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'category' => $request->category,
            // Hanya memperbarui foto jika ada foto baru
        ]);

        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = StockItem::findOrFail($id);
        // Hapus foto jika ada
        if ($item->photo) {
            $oldPath = str_replace('/storage/', '', $item->photo);
            Storage::disk('public')->delete($oldPath);
        }
        $item->delete();

        return response()->json(null, 204);
    }
}
