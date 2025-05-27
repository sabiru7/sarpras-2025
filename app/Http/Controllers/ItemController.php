<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $items = Item::all();
        return response()->json($items);
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'foto' => 'nullable|string',
        ]);

        $item = Item::create($request->all());
        return response()->json($item, 201); // 201 Created
    }

    // Display the specified resource
    public function show($id)
    {
        $item = Item::findOrFail($id);
        return response()->json($item);
    }

    // Update the specified resource in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'foto' => 'nullable|string',
        ]);

        $item = Item::findOrFail($id);
        $item->update($request->all());
        return response()->json($item);
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return response()->json(null, 204); // 204 No Content
    }
}
