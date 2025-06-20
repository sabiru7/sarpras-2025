@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detail Peminjaman</h1>
        <div class="card p-4 mb-4">
            <p><strong>Nama Peminjam:</strong> {{ $loan->user->name }}</p>
            <p><strong>Barang:</strong> {{ $loan->item->name }}</p>
            <p><strong>Jumlah:</strong> {{ $loan->quantity }}</p>
            <p><strong>Tanggal Pinjam:</strong> {{ $loan->loan_date }}</p>
            <p><strong>Status:</strong>
                @if ($loan->status === 'approved')
                    <span class="badge bg-success">Disetujui</span>
                @elseif($loan->status === 'pending')
                    <span class="badge bg-warning">Menunggu</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($loan->status) }}</span>
                @endif
            </p>
        </div>

        @if ($loan->status === 'approved')
            <a href="{{ route('returns.create', $loan->id) }}" class="btn btn-primary">
                Ajukan Pengembalian
            </a>
        @endif

        <a href="{{ route('loans.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
@endsection
