<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Management</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Tailwind for minor styling -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(to right, #e0f7fa, #f1f5f9);
      font-family: 'Segoe UI', sans-serif;
    }
    .overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.4);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }
    .floating-form {
      background: white;
      padding: 30px;
      border-radius: 1rem;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 500px;
      animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .table-hover tbody tr:hover {
      background-color: #f0f8ff;
    }
    .btn-custom:hover {
      transform: scale(1.05);
      transition: 0.2s;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold">User Management</h2>
    <button class="btn btn-primary btn-custom" onclick="openForm()">+ Add User</button>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
          <thead class="table-primary">
            <tr>
              <th>ID</th>
              <th>Email</th>
              <th>Role</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="user-table-body">
            <!-- Dynamic content -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Floating Form -->
<div class="overlay" id="form-overlay">
  <div class="floating-form">
    <h5 id="form-title" class="text-primary mb-3">Add User</h5>
    <form id="user-form">
      <input type="hidden" id="user-id" />
      <div class="mb-3">
        <label for="user-email" class="form-label">Email</label>
        <input type="email" id="user-email" class="form-control" required />
      </div>
      <div class="mb-3">
        <label for="user-password" class="form-label">Password</label>
        <input type="password" id="user-password" class="form-control" required />
      </div>
      <div class="mb-3">
        <label for="user-role" class="form-label">Role</label>
        <select id="user-role" class="form-select" required>
          <option value="" disabled selected>Select role</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary btn-custom" id="submit-btn">Add User</button>
        <button type="button" class="btn btn-outline-secondary" onclick="closeForm()">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Script -->
<script>
  let users = [];
  const apiBaseUrl = "http://localhost:8000/api/users";

  const userForm = document.getElementById('user-form');
  const formOverlay = document.getElementById('form-overlay');
  const formTitle = document.getElementById('form-title');
  const submitBtn = document.getElementById('submit-btn');

  const userId = document.getElementById('user-id');
  const userEmail = document.getElementById('user-email');
  const userPassword = document.getElementById('user-password');
  const userRole = document.getElementById('user-role');
  const userTableBody = document.getElementById('user-table-body');

  async function fetchUsers() {
    try {
      const res = await fetch(apiBaseUrl);
      users = await res.json();
      renderTable();
    } catch (err) {
      console.error("Error fetching users:", err);
    }
  }

  function renderTable() {
    userTableBody.innerHTML = '';
    users.forEach(user => {
      userTableBody.innerHTML += `
        <tr>
          <td>${user.id}</td>
          <td>${user.email}</td>
          <td>${user.role}</td>
          <td>
            <button class="btn btn-sm btn-warning me-1" onclick="startEditUser(${user.id})">Edit</button>
            <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">Delete</button>
          </td>
        </tr>
      `;
    });
  }

  userForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = userId.value;
    const payload = {
      email: userEmail.value,
      password: userPassword.value,
      role: userRole.value,
      name: "Anonymous"
    };

    try {
      if (id) {
        await fetch(`${apiBaseUrl}/${id}`, {
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

      closeForm();
      await fetchUsers();
    } catch (err) {
      console.error("Error saving user:", err);
    }
  });

  function openForm() {
    formTitle.textContent = "Add User";
    submitBtn.textContent = "Add User";
    userForm.reset();
    userId.value = '';
    formOverlay.style.display = 'flex';
  }

  function startEditUser(id) {
    const user = users.find(u => u.id === id);
    if (!user) return;

    formTitle.textContent = "Edit User";
    submitBtn.textContent = "Update User";
    userId.value = user.id;
    userEmail.value = user.email;
    userPassword.value = '';
    userRole.value = user.role;
    formOverlay.style.display = 'flex';
  }

  function closeForm() {
    userForm.reset();
    formOverlay.style.display = 'none';
  }

  async function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user?')) return;
    try {
      await fetch(`${apiBaseUrl}/${id}`, { method: 'DELETE' });
      await fetchUsers();
    } catch (err) {
      console.error("Error deleting user:", err);
    }
  }

  fetchUsers();
</script>

</body>
</html>
