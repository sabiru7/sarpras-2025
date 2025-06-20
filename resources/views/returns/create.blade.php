@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Ajukan Pengembalian</h1>

        <div class="card p-4 mb-4">
            <p><strong>Barang:</strong> {{ $loan->item->name }}</p>
            <p><strong>Jumlah Dipinjam:</strong> {{ $loan->quantity }}</p>
        </div>

        <form action="{{ route('returns.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="loan_id" value="{{ $loan->id }}">

            <div class="mb-3">
                <label>Jumlah yang dikembalikan</label>
                <input type="number" name="quantity" class="form-control" value="{{ $loan->quantity }}" min="1"
                    max="{{ $loan->quantity }}" required>
            </div>

            <div class="mb-3">
                <label>Foto Bukti Pengembalian</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label>Tanggal Pengembalian</label>
                <input type="date" name="return_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <button type="submit" class="btn btn-success">Kirim Permintaan Pengembalian</button>
            <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
