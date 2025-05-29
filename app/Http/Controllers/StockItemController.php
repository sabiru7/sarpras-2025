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
            'category' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $stockItem = new StockItem();
        $stockItem->name = $request->name;
        $stockItem->quantity = $request->quantity;
        $stockItem->category = $request->category;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $stockItem->photo = $path;
        }

        $stockItem->save();

        return response()->json($stockItem, 201);
    }

    public function show($id)
    {
        return StockItem::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'category' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $stockItem = StockItem::findOrFail($id);
        $stockItem->name = $request->name;
        $stockItem->quantity = $request->quantity;
        $stockItem->category = $request->category;

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($stockItem->photo) {
                Storage::disk('public')->delete($stockItem->photo);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $stockItem->photo = $path;
        }

        $stockItem->save();

        return response()->json($stockItem);
    }

    public function destroy($id)
    {
        $stockItem = StockItem::findOrFail($id);
        // Delete photo if exists
        if ($stockItem->photo) {
            Storage::disk('public')->delete($stockItem->photo);
        }
        $stockItem->delete();

        return response()->json(null, 204);
    }
}
