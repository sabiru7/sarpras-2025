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
    }
    header h1 {
      color: white;
      font-weight: 700;
    }
    .table thead th {
      background-color: #14b8a6;
      color: white;
      text-align: center;
    }
    .table tbody td {
      vertical-align: middle;
    }
    footer {
      background-color: #065f46;
      color: white;
    }
  </style>
</head>
<body class="min-vh-100 d-flex flex-column">

  <!-- Header -->
  <header class="py-4 shadow">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
      <h1 class="mb-2 mb-md-0">Laporan Barang Peminjaman</h1>
      <div class="text-white text-center md:text-right">
        <div class="mb-1">Tanggal Laporan: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</div>
      </div>
    </div>
  </header>

  <!-- Main content -->
  <main class="container my-5 flex-grow">

    <!-- Summary cards -->
    <section class="row g-3 mb-5 text-center">
      <div class="col-md-4">
        <div class="p-4 rounded-lg shadow bg-white">
          <div class="text-teal-600 font-extrabold text-3xl">{{ $totalDipinjam }}</div>
          <div class="text-gray-700 mt-2 font-semibold">Total Barang Dipinjam</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 rounded-lg shadow bg-white">
          <div class="text-teal-600 font-extrabold text-3xl">{{ $peminjamUnik }}</div>
          <div class="text-gray-700 mt-2 font-semibold">Peminjam Aktif</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 rounded-lg shadow bg-white">
          <div class="text-teal-600 font-extrabold text-3xl">{{ $persenTepat }}%</div>
          <div class="text-gray-700 mt-2 font-semibold">Barang Dikembalikan Tepat Waktu</div>
        </div>
      </div>
    </section>

    <!-- Table of loaned items -->
    <section>
      <h2 class="mb-4 text-teal-700 font-semibold text-xl">Detail Peminjaman Barang</h2>
      <div class="table-responsive shadow rounded-lg bg-white">
        <table class="table table-striped table-hover mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Barang</th>
              <th>Nama Peminjam</th>
              <th>Tanggal Pinjam</th>
              <th>Tanggal Kembali</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($borrowings as $i => $item)
              <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $item->stockItem->name ?? '-' }}</td>
                <td>
                  {{
                    $item->peminjam 
                    ?? $item->borrower_name 
                    ?? ($item->user->name ?? '-')
                  }}
                </td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d-m-Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d-m-Y') }}</td>
                <td class="text-center">
                  @switch($item->status)
                    @case('kembali')
                      <span class="badge bg-success">Kembali</span>
                      @break
                    @case('dipinjam')
                      <span class="badge bg-warning text-dark">Dipinjam</span>
                      @break
                    @case('terlambat')
                      <span class="badge bg-danger">Terlambat</span>
                      @break
                    @default
                      <span class="badge bg-secondary">-</span>
                  @endswitch
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>

  </main>

  <!-- Footer -->
  <footer class="py-3 mt-auto">
    <div class="container text-center">
      <p>© 2024 Sistem Peminjaman Barang. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>
