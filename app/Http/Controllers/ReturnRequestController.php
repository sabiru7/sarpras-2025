<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;
use App\Exports\ReturnRequestExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReturnRequestController extends Controller
{
    public function export()
    {
        return Excel::download(new ReturnRequestExport, 'return_requests.xlsx');
    }

    public function index()
    {
        $returns = ReturnRequest::with('loan.user', 'loan.item')->latest()->paginate(10);
        return view('returns.index', compact('returns'));
    }

    public function create($loanId)
    {
        $loan = Loan::with('item')->findOrFail($loanId);
        return view('returns.create', compact('loan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'return_date' => 'required|date',
        ]);

        $loan = Loan::findOrFail($request->loan_id);
        if ($request->quantity > $loan->quantity) {
            return back()->withErrors(['quantity' => 'Jumlah melebihi pinjaman'])->withInput();
        }

        ReturnRequest::create([
            'loan_id' => $request->loan_id,
            'return_date' => $request->return_date,
            'status' => 'pending',
        ]);

        return redirect()->route('returns.index')->with('success', 'Permintaan pengembalian diajukan.');
    }

    public function approve($id)
    {
        $return = ReturnRequest::findOrFail($id);
        $return->update(['status' => 'approved']);

        LogActivity::create([
            'user_id' => Auth::id(),
            'action' => 'approved',
            'log' => 'Pengembalian disetujui',
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Pengembalian ditandai selesai.');
    }

    public function reject($id)
    {
        $return = ReturnRequest::findOrFail($id);
        $return->update(['status' => 'rejected']);
        return back()->with('error', 'Pengembalian ditolak.');
    }
}
