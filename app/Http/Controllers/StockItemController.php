<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StockItemController extends Controller
{
    private $imageFolder = 'uploads/stock_items';

    public function index()
    {
        return StockItem::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $this->uploadPhoto($request);

        $item = StockItem::create([
            'name' => $validated['name'],
            'quantity' => $validated['quantity'],
            'category' => $validated['category'],
            'photo' => $path,
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $this->deletePhoto($item->photo);
            $item->photo = $this->uploadPhoto($request);
        }

        $item->update([
            'name' => $validated['name'],
            'quantity' => $validated['quantity'],
            'category' => $validated['category'],
        ]);

        $item->save();

        return response()->json($item);
    }

    // Method khusus update foto saja
    public function updatePhoto(Request $request, $id)
    {
        $item = StockItem::findOrFail($id);

        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Hapus foto lama
        $this->deletePhoto($item->photo);

        // Upload foto baru
        $item->photo = $this->uploadPhoto($request);
        $item->save();

        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = StockItem::findOrFail($id);
        $this->deletePhoto($item->photo);
        $item->delete();

        return response()->json(null, 204);
    }

    private function uploadPhoto(Request $request): ?string
    {
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store($this->imageFolder, 'public');
            return Storage::url($path);
        }
        return null;
    }

    private function deletePhoto(?string $url): void
    {
        if ($url) {
            $path = str_replace('/storage/', '', $url);
            Storage::disk('public')->delete($path);
        }
    }
}
