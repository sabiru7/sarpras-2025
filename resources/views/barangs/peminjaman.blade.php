<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Peminjaman Barang</title>
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
    <h1 class="text-4xl font-bold text-indigo-700 mb-6 text-center">Form Peminjaman Barang</h1>

    {{-- Form Peminjaman --}}
    <form action="{{ route('peminjaman.store') }}" method="POST" class="mb-5">
      @csrf

      <div class="mb-4">
        <label for="stock_item_id" class="form-label">Pilih Barang</label>
        <select id="stock_item_id" name="stock_item_id" class="form-select" required>
          <option value="" disabled selected>-- Pilih Barang --</option>
          @foreach ($stockItems as $item)
            <option value="{{ $item->id }}">{{ $item->name }} (stok: {{ $item->quantity }})</option>
          @endforeach
        </select>
      </div>

      <div class="mb-4">
        <label for="jumlah" class="form-label">Jumlah</label>
        <input type="number" id="jumlah" name="jumlah" class="form-control" min="1" required />
      </div>

      <div class="mb-4">
        <label for="peminjam" class="form-label">Nama Peminjam</label>
        <input type="text" id="peminjam" name="peminjam" class="form-control" required />
      </div>

      <div class="mb-4">
        <label for="alasan" class="form-label">Deskripsi Alasan</label>
        <textarea id="alasan" name="alasan" class="form-control" rows="3" required></textarea>
      </div>

      <div class="mb-4">
        <label for="borrow_date" class="form-label">Tanggal Peminjaman</label>
        <input type="datetime-local" id="borrow_date" name="borrow_date" class="form-control" required />
      </div>

      <div class="mb-4">
        <label for="return_date" class="form-label">Tanggal Pengembalian (opsional)</label>
        <input type="datetime-local" id="return_date" name="return_date" class="form-control" />
      </div>

      <button type="submit" class="btn btn-primary w-100">Pinjam</button>
    </form>

    <hr class="my-6" />

    {{-- Riwayat Peminjaman --}}
    <h2 class="text-3xl font-semibold mb-4">Riwayat Peminjaman</h2>

    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Peminjam</th>
            <th>Alasan</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($borrowings as $borrowing)
            <tr>
              <td class="text-center" style="width: 90px;">
                @if ($borrowing->stockItem->photo)
                  <img src="{{ $borrowing->stockItem->photo }}" alt="Foto" class="img-thumbnail" width="80" />
                @else
                  <span class="text-muted">Tidak ada</span>
                @endif
              </td>
              <td>{{ $borrowing->stockItem->name }}</td>
              <td>{{ $borrowing->jumlah }}</td>
              <td>{{ $borrowing->borrower_name ?? $borrowing->peminjam }}</td>
              <td>{{ $borrowing->alasan }}</td>
              <td class="text-nowrap">
                {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d-m-Y H:i') }}
              </td>
              <td class="text-nowrap">
                {{ $borrowing->return_date ? \Carbon\Carbon::parse($borrowing->return_date)->format('d-m-Y H:i') : '-' }}
              </td>
              <td class="text-center">
                @switch($borrowing->status)
                  @case('menunggu')
                    <span class="badge bg-info text-dark">Menunggu</span>
                    @break
                  @case('disetujui')
                    <span class="badge bg-primary text-white">Disetujui</span>
                    @break
                  @case('dipinjam')
                    <span class="badge bg-warning text-dark">Dipinjam</span>
                    @break
                  @case('kembali')
                    <span class="badge bg-success">Kembali</span>
                    @break
                  @case('terlambat')
                    <span class="badge bg-danger">Terlambat</span>
                    @break
                  @case('ditolak')
                    <span class="badge bg-danger">Ditolak</span>
                    @break
                  @default
                    <span class="badge bg-secondary">-</span>
                @endswitch
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center">Belum ada peminjaman.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
