<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pengembalian Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f8fafc;
        }
        .table thead {
            background-color: #4f46e5;
            color: white;
        }
    </style>
</head>
<body class="p-4">

    <div class="container bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-4xl font-bold text-indigo-700 mb-6 text-center">Form Pengembalian Barang</h1>

        <!-- Tampilkan pesan sukses atau error -->
        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Pengembalian -->
        <form action="{{ route('pengembalian.store') }}" method="POST" class="mb-5">
            @csrf

            <div class="mb-4">
                <label for="borrowing_id" class="form-label">Pilih Peminjaman</label>
                <select id="borrowing_id" name="borrowing_id" class="form-select" required>
                    <option value="" disabled {{ old('borrowing_id') ? '' : 'selected' }}>-- Pilih Peminjaman --</option>
                    @foreach ($borrowings as $borrowing)
                        <option value="{{ $borrowing->id }}" {{ old('borrowing_id') == $borrowing->id ? 'selected' : '' }}>
                            {{ $borrowing->stockItem->name }} (Peminjam: {{ $borrowing->borrower_name ?? $borrowing->peminjam }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian</label>
                <input 
                    type="datetime-local" 
                    id="tanggal_pengembalian" 
                    name="tanggal_pengembalian" 
                    class="form-control" 
                    required 
                    value="{{ old('tanggal_pengembalian') }}"
                />
            </div>

            <div class="mb-4">
                <label for="keterangan" class="form-label">Keterangan (opsional)</label>
                <textarea 
                    id="keterangan" 
                    name="keterangan" 
                    class="form-control" 
                    rows="3"
                >{{ old('keterangan') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Kembalikan</button>
        </form>

        <hr class="my-6" />

        <!-- Riwayat Pengembalian -->
        <h2 class="text-3xl font-semibold mb-4">Riwayat Pengembalian</h2>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengembalians as $return)
                        <tr>
                            <td>{{ $return->borrowing->stockItem->name }}</td>
                            <td>{{ $return->borrowing->borrower_name ?? $return->borrowing->peminjam }}</td>
                            <td class="text-nowrap">
                                {{ \Carbon\Carbon::parse($return->borrowing->borrow_date)->format('d-m-Y H:i') }}
                            </td>
                            <td class="text-nowrap">
                                {{ \Carbon\Carbon::parse($return->tanggal_pengembalian)->format('d-m-Y H:i') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">Kembali</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada pengembalian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
