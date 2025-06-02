<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Persetujuan Peminjaman</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-color: #f1f5f9;
    }
    .table thead {
      background-color: #4f46e5;
      color: white;
    }
    .btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }
  </style>
</head>
<body class="p-6">

  <div class="container max-w-7xl mx-auto bg-white rounded-xl shadow p-6">
    <header class="mb-6 text-center">
      <h1 class="text-4xl font-bold text-indigo-700">Persetujuan Peminjaman Barang</h1>
      <p class="text-gray-600">Tinjau dan proses permintaan peminjaman barang.</p>
    </header>

    {{-- Alert Notifikasi --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @elseif(session('danger'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('danger') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Tabel Persetujuan -->
    <div class="overflow-auto border rounded shadow">
      <table class="table table-bordered w-full mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Peminjam</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($borrowings as $borrowing)
            <tr>
              <td>{{ $borrowing->id }}</td>
              <td>{{ $borrowing->stockItem->name ?? '-' }}</td>
              <td>{{ $borrowing->stockItem->category ?? '-' }}</td>
              <td>{{ $borrowing->jumlah }}</td>
              <td>{{ $borrowing->borrower_name }}</td>
              <td>
                @php
                  $status = $borrowing->status;
                  $badgeClass = match($status) {
                    'menunggu' => 'bg-warning text-dark',
                    'disetujui' => 'bg-success text-white',
                    'ditolak' => 'bg-danger text-white',
                    'dipinjam' => 'bg-primary text-white',
                    'kembali' => 'bg-secondary text-white',
                    default => 'bg-light text-dark',
                  };
                @endphp
                <span class="badge {{ $badgeClass }}">
                  {{ ucfirst($status) }}
                </span>
              </td>
              <td class="text-center">
                <form action="{{ route('approval.approve', $borrowing->id) }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-success"
                    {{ $borrowing->status !== 'menunggu' ? 'disabled' : '' }}>
                    Setujui
                  </button>
                </form>
                <form action="{{ route('approval.reject', $borrowing->id) }}" method="POST" class="d-inline ms-2">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-danger"
                    {{ $borrowing->status !== 'menunggu' ? 'disabled' : '' }}>
                    Tolak
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted">Tidak ada permintaan menunggu.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
