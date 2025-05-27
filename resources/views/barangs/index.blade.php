<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manajemen Barang dengan Foto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #eef2f7;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .floating-card {
      background: white;
      padding: 2rem;
      border-radius: 1rem;
      width: 460px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .table-container {
      max-height: 280px;
      overflow-y: auto;
    }
    table img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 6px;
    }
    .img-placeholder {
      width: 50px;
      height: 50px;
      background: #ced4da;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
      font-size: 1.3rem;
      user-select: none;
    }
    .action-buttons {
      display: flex;
      gap: 5px;
    }
  </style>
</head>
<body>
  <div class="floating-card">
    <h3 class="mb-4 text-center text-primary">Manajemen Stok Barang dengan Foto</h3>
    <form id="barang-form" class="mb-4">
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Barang</label>
        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama barang" required />
      </div>
      <div class="mb-3">
        <label for="jumlah" class="form-label">Stok (Jumlah)</label>
        <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" placeholder="Jumlah stok" required />
      </div>
      <div class="mb-3">
        <label for="foto" class="form-label">Foto Barang (opsional)</label>
        <input class="form-control" type="file" id="foto" name="foto" accept="image/*" />
      </div>
      <button type="submit" class="btn btn-primary w-100">Tambah Barang</button>
    </form>
    <div class="table-container">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-primary">
          <tr>
            <th scope="col">No.</th>
            <th scope="col">Foto</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Stok</th>
            <th scope="col">Aksi</th>
          </tr>
        </thead>
        <tbody id="table-barang-body">
          <tr><td colspan="5" class="text-center text-muted fst-italic py-3">Belum ada data barang</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
