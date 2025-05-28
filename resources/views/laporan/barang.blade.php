<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Stock Management</title>
<!-- Bootstrap CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<style>
  body {
    background-color: #f8fafc; /* Light background */
    min-height: 100vh;
  }
  .card-header {
    background: #4f46e5; /* Darker blue for header */
    color: white;
  }
  .btn-custom {
    @apply bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded shadow px-4 py-2 transition duration-300;
  }
  .btn-custom-danger {
    @apply bg-red-600 hover:bg-red-700 text-white font-semibold rounded shadow px-4 py-2 transition duration-300;
  }
  .btn-custom-secondary {
    @apply bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded shadow px-4 py-2 transition duration-300;
  }
  .table thead {
    background-color: #4f46e5; /* Darker blue for table header */
    color: white;
  }
  input[type="file"] {
    cursor: pointer;
  }
</style>
</head>
<body class="p-5">

<div class="container max-w-7xl mx-auto bg-white rounded-xl shadow-lg p-6">
  <header class="mb-6 text-center">
    <h1 class="text-4xl font-extrabold text-indigo-700 mb-2">Stok Barang</h1>
    <p class="text-gray-600">Manajemen barang dengan foto, kategori dan kontrol lengkap</p>
  </header>

  <!-- Add/Edit Form Modal -->
  <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="itemForm" class="modal-content">
        <div class="modal-header bg-indigo-600 text-white">
          <h5 class="modal-title" id="itemModalLabel">Tambah Barang</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body space-y-4">
          <div>
            <label for="itemName" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="itemName" required />
          </div>

          <div>
            <label for="itemQuantity" class="form-label">Jumlah</label>
            <input type="number" min="1" class="form-control" id="itemQuantity" required />
          </div>

          <div>
            <label for="itemCategory" class="form-label">Kategori</label>
            <select id="itemCategory" class="form-select" required>
              <option value="" disabled selected>Pilih kategori</option>
              <option value="Elektronik">Elektronik</option>
              <option value="Pakaian">Pakaian</option>
              <option value="Makanan">Makanan</option>
              <option value="Peralatan">Peralatan</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>

          <div>
            <label class="form-label block mb-1">Foto Barang <span class="text-sm text-gray-400">(jpg, png, max 2MB)</span></label>
            <input type="file" accept="image/png, image/jpeg" class="form-control" id="itemPhoto" />
            <img id="photoPreview" src="" alt="Preview Foto" class="mt-3 rounded-md shadow-md max-h-48 object-contain hidden" />
          </div>

          <input type="hidden" id="itemId" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-custom-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-custom">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Controls -->
  <div class="mb-4 flex justify-between items-center">
    <button id="addItemBtn" class="btn btn-custom flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      Tambah Barang
    </button>
    <div class="text-sm text-gray-600 italic">* Data tersimpan secara lokal di browser</div>
  </div>

  <!-- Stock Table -->
  <div class="overflow-x-auto rounded-lg shadow border border-gray-300">
    <table class="table-auto w-full text-left">
      <thead>
        <tr>
          <th class="px-4 py-2">Foto</th>
          <th class="px-4 py-2">Nama Barang</th>
          <th class="px-4 py-2">Kategori</th>
          <th class="px-4 py-2">Jumlah</th>
          <th class="px-4 py-2 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody id="stockTableBody" class="divide-y divide-gray-300">
        <!-- Dynamic rows here -->
      </tbody>
    </table>
  </div>
</div>

<!-- Bootstrap JS with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  (() => {
    const stockTableBody = document.getElementById('stockTableBody');
    const itemForm = document.getElementById('itemForm');
    const itemModal = new bootstrap.Modal(document.getElementById('itemModal'));
    const addItemBtn = document.getElementById('addItemBtn');
    const itemModalLabel = document.getElementById('itemModalLabel');
    const itemName = document.getElementById('itemName');
    const itemQuantity = document.getElementById('itemQuantity');
    const itemCategory = document.getElementById('itemCategory');
    const itemPhoto = document.getElementById('itemPhoto');
    const photoPreview = document.getElementById('photoPreview');
    const itemId = document.getElementById('itemId');

    // Load stock from localStorage or empty
    let stock = JSON.parse(localStorage.getItem('stockItems')) || [];

    // Render stock table rows
    function renderTable() {
      stockTableBody.innerHTML = '';
      if(stock.length === 0) {
        stockTableBody.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada data barang</td></tr>';
        return;
      }
      stock.forEach((item, index) => {
        const tr = document.createElement('tr');
        tr.classList.add('bg-white');
        tr.innerHTML = `
          <td class="px-4 py-2">
            <img src="${item.photo}" alt="${item.name}" class="w-16 h-16 object-cover rounded-md shadow" />
          </td>
          <td class="px-4 py-2 align-middle">${item.name}</td>
          <td class="px-4 py-2 align-middle">${item.category}</td>
          <td class="px-4 py-2 align-middle">${item.quantity}</td>
          <td class="px-4 py-2 text-center align-middle space-x-2">
            <button class="btn btn-sm btn-primary btn-custom" data-action="edit" data-index="${index}" aria-label="Edit ${item.name}">
              Edit
            </button>
            <button class="btn btn-sm btn-danger btn-custom-danger" data-action="delete" data-index="${index}" aria-label="Hapus ${item.name}">
              Hapus
            </button>
          </td>
        `;
        stockTableBody.appendChild(tr);
      });
    }

    // Save stock to localStorage
    function saveStock() {
      localStorage.setItem('stockItems', JSON.stringify(stock));
    }

    // Clear form
    function resetForm() {
      itemName.value = '';
      itemQuantity.value = '';
      itemCategory.value = '';
      itemPhoto.value = '';
      photoPreview.src = '';
      photoPreview.classList.add('hidden');
      itemId.value = '';
    }

    // Show modal to add new item
    addItemBtn.addEventListener('click', () => {
      resetForm();
      itemModalLabel.textContent = 'Tambah Barang';
      itemModal.show();
    });

    // Preview photo when file is selected
    itemPhoto.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if(!file) {
        photoPreview.src = '';
        photoPreview.classList.add('hidden');
        return;
      }
      if(!file.type.startsWith('image/')) {
        alert('File harus berupa gambar (jpg/png).');
        itemPhoto.value = '';
        return;
      }
      if(file.size > 2 * 1024 * 1024) {
        alert('Ukuran file maksimal 2MB.');
        itemPhoto.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = (e) => {
        photoPreview.src = e.target.result;
        photoPreview.classList.remove('hidden');
      };
      reader.readAsDataURL(file);
    });

    // Add or edit item on form submit
    itemForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const nameVal = itemName.value.trim();
      const quantityVal = parseInt(itemQuantity.value, 10);
      const categoryVal = itemCategory.value;
      if(!nameVal || quantityVal < 1 || !categoryVal) {
        alert('Mohon isi nama barang, kategori, dan jumlah dengan benar.');
        return;
      }

      const existingId = itemId.value;

      // Need to get photo either from preview or existing stock if editing without changing photo
      const file = itemPhoto.files[0];

      if(file) {
        // Use file reader to get dataURL
        const reader = new FileReader();
        reader.onload = (ev) => {
          const photoData = ev.target.result;
          saveItem(existingId, nameVal, categoryVal, quantityVal, photoData);
        };
        reader.readAsDataURL(file);
      } else {
        // No new photo file selected 
        if(existingId) {
          // Use already stored photo for editing
          const idx = parseInt(existingId, 10);
          if(stock[idx]) {
            saveItem(existingId, nameVal, stock[idx].category, quantityVal, stock[idx].photo);
          } else {
            alert('Data barang tidak ditemukan.');
          }
        } else {
          alert('Mohon pilih foto barang.');
        }
      }
    });

    // Save an item to the stock list
    function saveItem(id, name, category, quantity, photo) {
      const idx = id ? parseInt(id, 10) : -1;
      if(idx >= 0 && idx < stock.length) {
        // Edit existing
        stock[idx] = { name, category, quantity, photo };
      } else {
        // Add new item
        stock.push({ name, category, quantity, photo });
      }
      saveStock();
      renderTable();
      resetForm();
      itemModal.hide();
    }

    // Handle edit and delete buttons using event delegation
    stockTableBody.addEventListener('click', (e) => {
      const btn = e.target.closest('button');
      if(!btn) return;
      const action = btn.getAttribute('data-action');
      const index = parseInt(btn.getAttribute('data-index'), 10);
      if(isNaN(index)) return;
      if(action === 'edit') {
        editItem(index);
      } else if(action === 'delete') {
        deleteItem(index);
      }
    });

    // Edit item
    function editItem(index) {
      const item = stock[index];
      if(!item) return;
      itemName.value = item.name;
      itemQuantity.value = item.quantity;
      itemCategory.value = item.category;
      photoPreview.src = item.photo;
      photoPreview.classList.remove('hidden');
      itemPhoto.value = '';
      itemId.value = index.toString();
      itemModalLabel.textContent = 'Edit Barang';
      itemModal.show();
    }

    // Delete item with confirmation
    function deleteItem(index) {
      if(confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
        stock.splice(index, 1);
        saveStock();
        renderTable();
      }
    }

    // Initial render on page load
    renderTable();

  })();
</script>

</body>
</html>
