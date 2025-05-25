<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manajemen Akun User</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Custom scrollbar for table */
    .scrollbar-thin::-webkit-scrollbar {
      height: 8px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
      background-color: #94a3b8;
      border-radius: 4px;
    }
  </style>
</head>
<body class="bg-gradient-to-r from-blue-100 via-blue-50 to-white min-h-screen font-sans text-gray-800">
  <header class="bg-blue-600 shadow-lg">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-white font-bold text-2xl select-none">Manajemen Akun User</h1>
      <button id="btnAddUser" class="px-4 py-2 bg-white text-blue-600 font-semibold rounded shadow hover:bg-blue-50 transition">
        + Tambah User
      </button>
    </div>
  </header>
  <main class="max-w-7xl mx-auto mt-8 px-6">
    <div class="overflow-x-auto scrollbar-thin rounded-lg shadow-md bg-white">
      <table class="min-w-full border-collapse border border-gray-200">
        <thead class="bg-blue-50 text-blue-800 font-semibold uppercase text-sm">
          <tr>
            <th class="border border-blue-100 p-3 text-left">#</th>
            <th class="border border-blue-100 p-3 text-left">Nama</th>
            <th class="border border-blue-100 p-3 text-left">Email</th>
            <th class="border border-blue-100 p-3 text-left">Role</th>
            <th class="border border-blue-100 p-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody id="userTableBody" class="divide-y divide-gray-100 text-gray-700"></tbody>
      </table>
    </div>
  </main>

  <!-- Modal Background -->
  <div id="modalBg" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white rounded-lg w-96 max-w-full p-6 shadow-lg relative">
      <h2 id="modalTitle" class="text-xl font-semibold mb-4">Tambah User</h2>
      <form id="userForm" class="space-y-4">
        <input type="hidden" id="userId" />
        <div>
          <label for="name" class="block text-gray-700 font-medium mb-1">Nama</label>
          <input type="text" id="name" class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-blue-400" placeholder="Masukkan nama" required />
        </div>
        <div>
          <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
          <input type="email" id="email" class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-blue-400" placeholder="Masukkan email" required />
        </div>
        <div>
          <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
          <input type="password" id="password" class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-blue-400" placeholder="Masukkan password" required />
        </div>
        <div>
          <label for="role" class="block text-gray-700 font-medium mb-1">Role</label>
          <select id="role" class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-blue-400" required>
            <option value="">Pilih role</option>    
            <option value="User">User</option>
            <option value="Admin">Admin</option>
          </select>
        </div>
        <div class="flex justify-end space-x-3">
          <button type="button" id="btnCancel" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100 transition">Batal</button>
          <button type="submit" id="btnSave" class="px-4 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">Simpan</button>
        </div>
      </form>
      <button id="modalClose" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-xl font-bold">&times;</button>
    </div>
  </div>

  <div id="notification" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded shadow-lg hidden"></div>

  <script>
    const modalBg = document.getElementById('modalBg');
    const modalTitle = document.getElementById('modalTitle');
    const btnAddUser = document.getElementById('btnAddUser');
    const btnCancel = document.getElementById('btnCancel');
    const modalClose = document.getElementById('modalClose');
    const userForm = document.getElementById('userForm');
    const userIdInput = document.getElementById('userId');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const roleInput = document.getElementById('role');
    const userTableBody = document.getElementById('userTableBody');
    const notification = document.getElementById('notification');

    let editIndex = null;

    function resetForm() {
      userIdInput.value = '';
      nameInput.value = '';
      emailInput.value = '';
      passwordInput.value = '';
      roleInput.value = '';
      editIndex = null;
    }

    function showModal(edit = false, userData = null) {
      modalBg.classList.remove('hidden');
      if (edit) {
        modalTitle.textContent = 'Edit User';
        userIdInput.value = userData.id;
        nameInput.value = userData.name;
        emailInput.value = userData.email;
        roleInput.value = userData.role;
        passwordInput.value = ''; // Don't pre-fill password for security
      } else {
        modalTitle.textContent = 'Tambah User';
        resetForm();
      }
    }

    function closeModal() {
      modalBg.classList.add('hidden');
      resetForm();
    }

    async function fetchUsers() {
      const response = await fetch('/api/users');
      const users = await response.json();
      userTableBody.innerHTML = '';

      users.forEach((user, index) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td class="border border-gray-100 p-3">${index + 1}</td>
          <td class="border border-gray-100 p-3">${user.name}</td>
          <td class="border border-gray-100 p-3">${user.email}</td>
          <td class="border border-gray-100 p-3">${user.role}</td>
          <td class="border border-gray-100 p-3 text-center space-x-2">
            <button class="btnEditUser px-3 py-1 bg-yellow-300 text-yellow-900 rounded hover:bg-yellow-400 transition">Edit</button>
            <button class="btnDeleteUser px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Hapus</button>
          </td>
        `;
        userTableBody.appendChild(tr);
      });

      bindActionButtons();
    }

    function showNotification(message) {
      notification.textContent = message;
      notification.classList.remove('hidden');
      setTimeout(() => {
        notification.classList.add('hidden');
      }, 3000);
    }

    btnAddUser.addEventListener('click', () => showModal());
    btnCancel.addEventListener('click', closeModal);
    modalClose.addEventListener('click', closeModal);

    userForm.addEventListener('submit', async e => {
      e.preventDefault();

      const name = nameInput.value.trim();
      const email = emailInput.value.trim();
      const password = passwordInput.value.trim();
      const role = roleInput.value;

      if (!name || !email || !password || !role) {
        alert("Semua field harus diisi!");
        return;
      }

      const method = userIdInput.value ? 'PUT' : 'POST';
      const url = userIdInput.value ? `/api/users/${userIdInput.value}` : '/api/users';

      const data = { name, email, role };
      if (!userIdInput.value) {
        data.password = password; // Include password only for new users
      }

      const response = await fetch(url, {
        method: method,
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
      });

      if (response.ok) {
        closeModal();
        fetchUsers();
        showNotification(userIdInput.value ? "User berhasil diperbarui!" : "User berhasil ditambahkan!");
      } else {
        alert("Terjadi kesalahan saat menyimpan data.");
      }
    });

    function bindActionButtons() {
      const editButtons = document.querySelectorAll('.btnEditUser');
      const deleteButtons = document.querySelectorAll('.btnDeleteUser');

      editButtons.forEach((btn, i) => {
        btn.onclick = async () => {
          const row = btn.closest('tr');
          const userData = {
            id: row.cells[0].textContent, // Assuming the first cell contains the user ID
            name: row.cells[1].textContent,
            email: row.cells[2].textContent,
            role: row.cells[3].textContent,
          };
          showModal(true, userData);
        };
      });

      deleteButtons.forEach((btn, i) => {
        btn.onclick = async () => {
          const row = btn.closest('tr');
          const userId = row.cells[0].textContent;
          if (confirm(`Hapus user "${row.cells[1].textContent}"?`)) {
            const response = await fetch(`/api/users/${userId}`, {
              method: 'DELETE',
            });

            if (response.ok) {
              fetchUsers();
              showNotification("User berhasil dihapus!");
            } else {
              alert("Terjadi kesalahan saat menghapus user.");
            }
          }
        };
      });
    }

    document.addEventListener('DOMContentLoaded', fetchUsers);
  </script>
</body>
</html>
