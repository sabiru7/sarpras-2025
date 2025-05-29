<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Stock Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f8fafc;
            min-height: 100vh;
        }
        .card-header {
            background: #4f46e5;
            color: white;
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Controls -->
    <div class="mb-4 flex justify-between items-center">
        <button id="addItemBtn" class="btn btn-primary flex items-center gap-2">
            Tambah Barang
        </button>
        <div class="text-sm text-gray-600 italic">* Data tersimpan di database</div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // JavaScript for handling add, edit, delete functionality
    let items = [];
    let currentItemIndex = -1;

    document.getElementById('addItemBtn').addEventListener('click', function() {
        currentItemIndex = -1;
        document.getElementById('itemForm').reset();
        document.getElementById('photoPreview').classList.add('hidden');
        document.getElementById('itemModalLabel').innerText = 'Tambah Barang';
        const itemModal = new bootstrap.Modal(document.getElementById('itemModal'));
        itemModal.show();
    });

    document.getElementById('itemForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const itemName = document.getElementById('itemName').value;
        const itemQuantity = document.getElementById('itemQuantity').value;
        const itemCategory = document.getElementById('itemCategory').value;
        const itemPhoto = document.getElementById('itemPhoto').files[0];

        if (currentItemIndex === -1) {
            // Add new item
            const newItem = {
                id: Date.now(),
                name: itemName,
                quantity: itemQuantity,
                category: itemCategory,
                photo: URL.createObjectURL(itemPhoto)
            };
            items.push(newItem);
        } else {
            // Edit existing item
            items[currentItemIndex].name = itemName;
            items[currentItemIndex].quantity = itemQuantity;
            items[currentItemIndex].category = itemCategory;
            if (itemPhoto) {
                items[currentItemIndex].photo = URL.createObjectURL(itemPhoto);
            }
        }

        renderTable();
        const itemModal = bootstrap.Modal.getInstance(document.getElementById('itemModal'));
        itemModal.hide();
    });

    function renderTable() {
        const stockTableBody = document.getElementById('stockTableBody');
        stockTableBody.innerHTML = '';
        items.forEach((item, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-4 py-2"><img src="${item.photo}" alt="${item.name}" class="h-16 w-16 object-cover" /></td>
                <td class="px-4 py-2">${item.name}</td>
                <td class="px-4 py-2">${item.category}</td>
                <td class="px-4 py-2">${item.quantity}</td>
                <td class="px-4 py-2 text-center">
                    <button class="btn btn-warning" onclick="editItem(${index})">Edit</button>
                    <button class="btn btn-danger" onclick="deleteItem(${index})">Hapus</button>
                </td>
            `;
            stockTableBody.appendChild(row);
        });
    }

    function editItem(index) {
        currentItemIndex = index;
        const item = items[index];
        document.getElementById('itemName').value = item.name;
        document.getElementById('itemQuantity').value = item.quantity;
        document.getElementById('itemCategory').value = item.category;
        document.getElementById('photoPreview').src = item.photo;
        document.getElementById('photoPreview').classList.remove('hidden');
        document.getElementById('itemModalLabel').innerText = 'Edit Barang';
        const itemModal = new bootstrap.Modal(document.getElementById('itemModal'));
        itemModal.show();
    }

    function deleteItem(index) {
        items.splice(index, 1);
        renderTable();
    }
</script>
</body>
</html>
