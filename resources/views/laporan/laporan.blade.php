<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Laporan Barang Peminjaman</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f3f4f6;
    }
    header {
      background-color: #0d9488;
      color: white;
    }
    .table thead th {
      background-color: #14b8a6;
      color: white;
      text-align: center;
    }
    .borrower-name {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .badge {
      font-size: 0.8rem;
      padding: 0.35em 0.6em;
    }
    footer {
      background-color: #065f46;
      color: white;
    }
  </style>
</head>
<body class="min-vh-100 d-flex flex-column">

  <!-- Header -->
  <header class="py-4 shadow-sm">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
      <h1 class="mb-2 mb-md-0 fs-4 fw-bold">Laporan Barang Peminjaman</h1>
      <div class="text-white text-md-end small">
        Tanggal Laporan:<br>
        <strong>{{ \Carbon\Carbon::parse($tanggalLaporan)->format('d-m-Y H:i') }}</strong>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container my-5 flex-grow-1">

    <!-- Summary Cards -->
    <section class="row g-3 mb-4 text-center">
      <div class="col-12 col-md-4">
        <div class="p-4 bg-white rounded shadow-sm h-100 d-flex flex-column justify-content-center">
          <h3 class="text-success fw-bold mb-1">{{ $totalDipinjam ?? 0 }}</h3>
          <p class="text-muted mb-0">Total Barang Dipinjam</p>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="p-4 bg-white rounded shadow-sm h-100 d-flex flex-column justify-content-center">
          <h3 class="text-info fw-bold mb-1">{{ $peminjamUnik ?? 0 }}</h3>
          <p class="text-muted mb-0">Peminjam Aktif</p>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="p-4 bg-white rounded shadow-sm h-100 d-flex flex-column justify-content-center">
          <h3 class="text-primary fw-bold mb-1">{{ $persenTepat ?? 0 }}%</h3>
          <p class="text-muted mb-0">Pengembalian Tepat Waktu</p>
        </div>
      </div>
    </section>

    <!-- Borrowing Table -->
    <section>
      <h2 class="mb-3 text-teal-700 fw-semibold fs-5">Detail Peminjaman Barang</h2>
      <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table table-bordered table-hover mb-0 align-middle">
          <thead>
            <tr>
              <th style="width: 5%;">No</th>
              <th>Nama Barang</th>
              <th>Nama Peminjam</th>
              <th style="width: 18%;">Tanggal Pinjam</th>
              <th style="width: 18%;">Tanggal Kembali</th>
              <th style="width: 12%;">Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($borrowings as $i => $item)
              <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $item->stockItem->name ?? '-' }}</td>
                <td class="borrower-name" title="{{ $item->peminjam ?? $item->borrower_name ?? $item->user->name ?? '-' }}">
                  {{ $item->peminjam ?? $item->borrower_name ?? $item->user->name ?? '-' }}
                </td>
              <td class="text-nowrap">
              {{ $item->borrow_date ? \Carbon\Carbon::parse($item->borrow_date)->format('d-m-Y H:i') : '-' }}
                </td>
              <td class="text-nowrap">
              {{ $item->return_date ? \Carbon\Carbon::parse($item->return_date)->format('d-m-Y H:i') : '-' }}
              </td>

                <td class="text-center">
                  @php
                    $status = $item->status;
                    $badgeClass = [
                      'kembali'   => 'bg-success',
                      'dipinjam'  => 'bg-warning text-dark',
                      'disetujui' => 'bg-primary',
                      'terlambat' => 'bg-danger',
                      'menunggu'  => 'bg-info text-dark',
                      'ditolak'   => 'bg-danger',
                    ][$status] ?? 'bg-secondary';
                  @endphp
                  <span class="badge {{ $badgeClass }}">
                    {{ ucfirst($status) }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted">Tidak ada data peminjaman.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

  </main>

  <!-- Footer -->
  <footer class="py-3 mt-auto">
    <div class="container text-center small">
      <p class="mb-0">© 2024 Sistem Peminjaman Barang. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>
