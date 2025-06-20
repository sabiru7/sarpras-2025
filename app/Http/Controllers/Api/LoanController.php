<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Item;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with(['user', 'item'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar peminjaman',
            'data' => $loans
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'borrowed_at' => 'required|date',
            'returned_at' => 'required|date|after:borrowed_at',
        ]);

        $loan = Loan::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'borrowed_at' => $request->borrowed_at,
            'returned_at' => $request->returned_at,
            'status' => 'pending',
        ]);

        LogActivity::create([
            'user_id' => Auth::id(),
            'action' => 'Peminjaman',
            'log' => 'Permintaan peminjaman dikirim',
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan peminjaman dikirim dan menunggu persetujuan.',
            'data' => $loan
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tidak diizinkan'], 403);
        }

        $loan->load(['item', 'user']);

        return response()->json([
            'success' => true,
            'message' => 'Detail peminjaman',
            'data' => $loan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
