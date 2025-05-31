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
    <form action="{{ route('peminjaman.store') }}" method="POST">
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

      <button type="submit" class="btn btn-primary w-full">Pinjam</button>
    </form>

    <hr class="my-6" />

    {{-- Riwayat Peminjaman --}}
    <h2 class="text-3xl font-semibold mb-4">Riwayat Peminjaman</h2>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Peminjam</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($borrowings as $borrowing)
            <tr>
              <td>
                @if ($borrowing->stockItem->photo)
                  <img src="{{ $borrowing->stockItem->photo }}" alt="Foto" class="img-thumbnail" width="80" />
                @else
                  <span class="text-muted">Tidak ada</span>
                @endif
              </td>
              <td>{{ $borrowing->stockItem->name }}</td>
              <td>{{ $borrowing->jumlah }}</td>
              <td>{{ $borrowing->peminjam }}</td>
              <td>{{ $borrowing->created_at->format('d M Y H:i') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">Belum ada peminjaman.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
