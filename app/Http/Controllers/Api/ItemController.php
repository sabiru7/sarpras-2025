<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with('category')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar barang',
            'data' => $items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:1',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'category_id', 'stock', 'description']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('items', 'public');
        }

        $item = Item::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan',
            'data' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Detail barang',
            'data' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:1',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'category_id', 'stock', 'description']);

        if ($request->hasFile('photo')) {
            // Hapus file lama jika ada
            if ($item->photo) {
                Storage::disk('public')->delete($item->photo);
            }
            $data['photo'] = $request->file('photo')->store('items', 'public');
        }

        $item->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diperbarui',
            'data' => $item
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        if ($item->photo) {
            Storage::disk('public')->delete($item->photo);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus'
        ]);
    }
}
