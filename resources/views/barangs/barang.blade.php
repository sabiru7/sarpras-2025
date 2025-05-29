<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manajemen Stok Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-color: #f1f5f9;
    }
    .table thead {
      background-color: #4f46e5;
      color: white;
    }
    input[type="file"] {
      cursor: pointer;
    }
  </style>
</head>
<body class="p-6">

  <div class="container max-w-7xl mx-auto bg-white rounded-xl shadow p-6">
    <header class="mb-6 text-center">
      <h1 class="text-4xl font-bold text-indigo-700">Manajemen Stok Barang</h1>
      <p class="text-gray-600">Tambah, edit, dan kelola stok barang dengan gambar dan kategori</p>
    </header>

    <!-- Modal -->
    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="itemForm" class="modal-content" enctype="multipart/form-data">
          <div class="modal-header bg-indigo-600 text-white">
            <h5 class="modal-title" id="itemModalLabel">Tambah Barang</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body space-y-4">
            <div>
              <label for="itemName" class="form-label">Nama Barang</label>
              <input type="text" class="form-control" id="itemName" name="name" required />
            </div>
            <div>
              <label for="itemQuantity" class="form-label">Jumlah</label>
              <input type="number" min="1" class="form-control" id="itemQuantity" name="quantity" required />
            </div>
            <div>
              <label for="itemCategory" class="form-label">Kategori</label>
              <select id="itemCategory" class="form-select" name="category" required>
                <option value="" disabled selected>Pilih kategori</option>
                <option value="Kabel">Kabel</option>
                <option value="Infocus">Infocus</option>
                <option value="Peralatan">Peralatan</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
            <div>
              <label class="form-label">Foto Barang <small class="text-muted">(jpg/png max 2MB)</small></label>
              <input type="file" class="form-control" id="itemPhoto" name="photo" accept="image/jpeg,image/png" />
              <img id="photoPreview" class="mt-2 rounded shadow max-h-40 hidden" alt="Preview Foto Barang" />
            </div>
            <input type="hidden" id="itemId" name="id" />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Kontrol -->
    <div class="mb-4 flex justify-between items-center">
      <button id="addItemBtn" class="btn btn-primary">+ Tambah Barang</button>
      <span class="text-sm italic text-gray-500">* Data disimpan ke backend</span>
    </div>

    <!-- Tabel -->
    <div class="overflow-auto border rounded shadow">
      <table class="table table-bordered w-full">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody id="stockTableBody"></tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Inisialisasi modal Bootstrap
    const itemModal = new bootstrap.Modal(document.getElementById('itemModal'));
    const photoInput = document.getElementById('itemPhoto');
    const photoPreview = document.getElementById('photoPreview');
    let currentItemId = null;

    // Tombol tambah barang
    document.getElementById('addItemBtn').addEventListener('click', () => {
      currentItemId = null;
      resetForm();
      document.getElementById('itemModalLabel').innerText = 'Tambah Barang';
      itemModal.show();
    });

    // Preview foto saat pilih file
    photoInput.addEventListener('change', () => {
      const file = photoInput.files[0];
      if (file && file.size > 2 * 1024 * 1024) {
        alert('Ukuran file terlalu besar. Maksimal 2MB.');
        photoInput.value = '';
        photoPreview.classList.add('hidden');
        photoPreview.src = '';
        return;
      }
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          photoPreview.src = e.target.result;
          photoPreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
      } else {
        photoPreview.classList.add('hidden');
        photoPreview.src = '';
      }
    });

    // Submit form tambah/edit barang
    document.getElementById('itemForm').addEventListener('submit', async (event) => {
      event.preventDefault();

      const formData = new FormData(event.target);

      let url = '/api/stock-items';
      let method = 'POST';

      if (currentItemId) {
        url = `/api/stock-items/${currentItemId}`;
        method = 'POST'; // Gunakan POST kalau backendmu menerima update lewat POST + _method=PUT
        formData.append('_method', 'PUT'); // Jika backend Laravel, ini perlu untuk override method
      }

      try {
        const response = await fetch(url, {
          method: method,
          body: formData
        });

        if (!response.ok) {
          const err = await response.text();
          throw new Error(err || 'Gagal menyimpan data');
        }
        await renderTable();
        itemModal.hide();
      } catch (error) {
        alert('Error: ' + error.message);
      }
    });

    // Fungsi render tabel dari API
    async function renderTable() {
      const tbody = document.getElementById('stockTableBody');
      tbody.innerHTML = '<tr><td colspan="5" class="text-center py-3 text-gray-400">Memuat...</td></tr>';
      try {
        const response = await fetch('/api/stock-items');
        if (!response.ok) throw new Error('Gagal mengambil data');
        const data = await response.json();

        if (!Array.isArray(data) || data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="5" class="text-center py-3 text-gray-400">Data kosong</td></tr>';
          return;
        }

        tbody.innerHTML = '';
        data.forEach(item => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td><img src="${item.photo || ''}" class="h-16 w-16 object-cover rounded" alt="Foto Barang"/></td>
            <td>${item.name}</td>
            <td>${item.category}</td>
            <td>${item.quantity}</td>
            <td class="text-center">
              <button class="btn btn-sm btn-warning me-2" onclick="editItem(${item.id})">Edit</button>
              <button class="btn btn-sm btn-danger" onclick="deleteItem(${item.id})">Hapus</button>
            </td>`;
          tbody.appendChild(tr);
        });
      } catch (error) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error: ${error.message}</td></tr>`;
      }
    }

    // Fungsi edit barang: ambil data dan buka modal dengan data terisi
    async function editItem(id) {
      try {
        const response = await fetch(`/api/stock-items/${id}`);
        if (!response.ok) throw new Error('Gagal mengambil data barang');
        const item = await response.json();

        currentItemId = id;
        resetForm();

        document.getElementById('itemName').value = item.name;
        document.getElementById('itemQuantity').value = item.quantity;
        document.getElementById('itemCategory').value = item.category;
        document.getElementById('itemId').value = item.id;

        if (item.photo) {
          photoPreview.src = item.photo;
          photoPreview.classList.remove('hidden');
        } else {
          photoPreview.classList.add('hidden');
          photoPreview.src = '';
        }

        // Reset file input supaya bisa upload ulang foto
        photoInput.value = '';

        document.getElementById('itemModalLabel').innerText = 'Edit Barang';
        itemModal.show();
      } catch (error) {
        alert('Error: ' + error.message);
      }
    }

    // Fungsi hapus barang
    async function deleteItem(id) {
      if (!confirm('Yakin ingin menghapus barang ini?')) return;

      try {
        const response = await fetch(`/api/stock-items/${id}`, { method: 'DELETE' });
        if (!response.ok) throw new Error('Gagal menghapus barang');
        await renderTable();
      } catch (error) {
        alert('Error: ' + error.message);
      }
    }

    // Reset form modal
    function resetForm() {
      const form = document.getElementById('itemForm');
      form.reset();
      photoPreview.classList.add('hidden');
      photoPreview.src = '';
      document.getElementById('itemId').value = '';
    }

    // Load data saat pertama kali halaman dibuka
    renderTable();
  </script>
</body>
</html>
