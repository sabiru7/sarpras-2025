<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Table</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    .form-container, .table-container {
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 12px;
      background: #f9f9f9;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2 class="text-center text-primary">Add / Edit User</h2>
    <form id="user-form">
      <div>
        <label for="user-email" class="form-label">Email</label>
        <input type="email" class="form-control" id="user-email" placeholder="Enter email" required />
      </div>
      <div>
        <label for="user-password" class="form-label">Password</label>
        <input type="password" class="form-control" id="user-password" placeholder="Enter password" required />
      </div>
      <div>
        <label for="user-role" class="form-label">Role</label>
        <select class="form-select" id="user-role" required>
          <option value="" disabled selected>Select role</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <input type="hidden" id="edit-user-id" />
      <button type="submit" class="btn btn-primary w-100 mt-3">Save User</button>
    </form>
  </div>

  <div class="table-container">
    <h2 class="text-center text-primary">User Table</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="user-table-body">
        <!-- User data will be inserted dynamically here -->
      </tbody>
    </table>
  </div>
<script>
  let users = [];
  let userIdCounter = 1; // Optional, kalau datanya statis, tapi nanti pakai dari DB

  const apiBaseUrl = "http://localhost:8000/api/users"; // Ganti sesuai route API Laravel

  const userForm = document.getElementById('user-form');
  const userTableBody = document.getElementById('user-table-body');

  async function fetchUsers() {
    const res = await fetch(apiBaseUrl);
    users = await res.json();
    renderTable();
  }

  userForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const email = document.getElementById('user-email').value;
    const password = document.getElementById('user-password').value;
    const role = document.getElementById('user-role').value;
    const editUserId = document.getElementById('edit-user-id').value;

    const payload = { email, password, role, name: 'Anonymous' };

    try {
      if (editUserId) {
        await fetch(`${apiBaseUrl}/${editUserId}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
      } else {
        await fetch(apiBaseUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
      }

      await fetchUsers();
      resetForm();
    } catch (error) {
      console.error('Error:', error);
    }
  });

  function renderTable() {
    userTableBody.innerHTML = '';
    users.forEach(user => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${user.id}</td>
        <td>${user.email}</td>
        <td>${user.role}</td>
        <td>
          <button class="btn btn-sm btn-warning me-2" onclick="editUser(${user.id})">Edit</button>
          <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">Delete</button>
        </td>
      `;
      userTableBody.appendChild(row);
    });
  }

  function editUser(id) {
    const user = users.find(u => u.id === id);
    if (user) {
      document.getElementById('user-email').value = user.email;
      document.getElementById('user-password').value = user.password || '';
      document.getElementById('user-role').value = user.role;
      document.getElementById('edit-user-id').value = user.id;
    }
  }

  async function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user?')) return;

    try {
      await fetch(`${apiBaseUrl}/${id}`, { method: 'DELETE' });
      await fetchUsers();
    } catch (error) {
      console.error('Delete error:', error);
    }
  }

  function resetForm() {
    userForm.reset();
    document.getElementById('edit-user-id').value = '';
  }

  // Load users on page load
  fetchUsers();
</script>

</body>
</html>
    