<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReturnRequestController extends Controller
{
    public function index()
    {
        $returns = ReturnRequest::with('loan.user', 'loan.item')
            ->whereHas('loan', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $returns
        ]);
    }

    public function show($id)
    {
        $return = ReturnRequest::with('loan.user', 'loan.item')
            ->whereHas('loan', fn($q) => $q->where('user_id', auth()->id()))
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $return
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_id' => 'required|exists:loans,id',
            'quantity' => 'required|integer|min:1',
            'return_date' => 'required|date',
            'photo' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $loan = Loan::where('id', $request->loan_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($request->quantity > $loan->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi jumlah pinjaman.'
            ], 400);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('return_photos', 'public');
        }

        $return = ReturnRequest::create([
            'loan_id' => $loan->id,
            'quantity' => $request->quantity,
            'return_date' => $request->return_date,
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan pengembalian berhasil diajukan.',
            'data' => $return
        ]);
    }

    public function approve($id)
    {
        $return = ReturnRequest::findOrFail($id);
        $return->status = 'approved';
        $return->save();

        return response()->json([
            'success' => true,
            'message' => 'Permintaan pengembalian disetujui.'
        ]);
    }

    public function reject($id)
    {
        $return = ReturnRequest::findOrFail($id);
        $return->status = 'rejected';
        $return->save();

        return response()->json([
            'success' => true,
            'message' => 'Permintaan pengembalian ditolak.'
        ]);
    }
}
