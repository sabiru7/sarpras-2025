<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manajemen Barang dengan Foto</title>
  <!-- Bootstrap 5 CSS CDN -->
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
      gap: 5px; /* Menambahkan jarak antara tombol */
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
      <table class="table table-striped table-hover mb-0" aria-label="Tabel daftar stok barang" role="grid">
        <thead class="table-primary">
          <tr>
            <th scope="col" style="width: 10%;">No.</th>
            <th scope="col" style="width: 60px;">Foto</th>
            <th scope="col">Nama Barang</th>
            <th scope="col" style="width: 25%;">Stok</th>
            <th scope="col" style="width: 15%;">Aksi</th>
          </tr>
        </thead>
        <tbody id="table-barang-body">
          <tr><td colspan="5" class="text-center text-muted fst-italic py-3">Belum ada data barang</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (() => {
      const form = document.getElementById('barang-form');
      const tbody = document.getElementById('table-barang-body');
      const STORAGE_KEY = 'barang-stok-list';
      let editIndex = -1; // Index untuk mengedit barang

      function loadBarang() {
        const data = localStorage.getItem(STORAGE_KEY);
        if (!data) return [];
        try {
          return JSON.parse(data);
        } catch {
          return [];
        }
      }

      function saveBarang(barangList) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(barangList));
      }

      function renderTable() {
        const barangList = loadBarang();
        tbody.innerHTML = '';

        if (barangList.length === 0) {
          tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted fst-italic py-3">Belum ada data barang</td></tr>';
          return;
        }

        barangList.forEach((barang, idx) => {
          const tr = document.createElement('tr');
          const imgHTML = barang.foto
            ? `<img src="${barang.foto}" alt="Foto ${barang.nama}" loading="lazy" />`
            : `<div class="img-placeholder" title="Tidak ada foto">&#128247;</div>`;
          tr.innerHTML = `
            <th scope="row">${idx + 1}</th>
            <td>${imgHTML}</td>
            <td>${barang.nama}</td>
            <td>${barang.jumlah}</td>
            <td>
              <div class="action-buttons">
                <button class="btn btn-warning btn-sm" onclick="editBarang(${idx})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteBarang(${idx})">Hapus</button>
              </div>
            </td>
          `;
          tbody.appendChild(tr);
        });
      }

      function readFileAsDataURL(file) {
        return new Promise((resolve, reject) => {
          const reader = new FileReader();
          reader.onerror = () => {
            reader.abort();
            reject(new DOMException("Problem reading input file."));
          };
          reader.onload = () => {
            resolve(reader.result);
          };
          reader.readAsDataURL(file);
        });
      }

      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const nama = form.nama.value.trim();
        const jumlah = parseInt(form.jumlah.value);
        const fotoFile = form.foto.files[0];

        if (!nama || jumlah < 1) {
          alert('Mohon isi nama barang dan jumlah yang valid.');
          return;
        }

        let fotoDataURL = null;
        if (fotoFile) {
          try {
            fotoDataURL = await readFileAsDataURL(fotoFile);
          } catch {
            alert('Gagal memuat foto. Silakan coba lagi.');
            return;
          }
        }

        let barangList = loadBarang();

        if (editIndex > -1) {
          // Jika sedang dalam mode edit
          barangList[editIndex] = { nama, jumlah, foto: fotoDataURL };
          editIndex = -1; // Reset editIndex
        } else {
          // Tambah barang baru
          barangList.push({ nama, jumlah, foto: fotoDataURL });
        }

        saveBarang(barangList);
        renderTable();
        form.reset();
        form.nama.focus();
      });

      window.editBarang = (index) => {
        const barangList = loadBarang();
        const barang = barangList[index];

        form.nama.value = barang.nama;
        form.jumlah.value = barang.jumlah;
        form.foto.value = ''; // Reset file input
        editIndex = index; // Set index untuk edit
      };

      window.deleteBarang = (index) => {
        let barangList = loadBarang();
        barangList.splice(index, 1); // Hapus barang dari list
        saveBarang(barangList);
        renderTable();
      };

      // Initial rendering
      renderTable();
    })();
  </script>
</body>
</html>
