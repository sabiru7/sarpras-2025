<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Table</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(135deg, #667eea, #764ba2);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
    }
    .table-container, .form-container {
      margin: 20px;
      background: white;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(118, 75, 162, 0.3);
      padding: 20px;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2 class="text-center text-purple-700">Add New User</h2>
    <form id="user-form" class="space-y-3">
      <div>
        <label for="user-name" class="form-label">Name</label>
        <input type="text" class="form-control" id="user-name" placeholder="Enter name" required />
      </div>
      <div>
        <label for="user-email" class="form-label">Email</label>
        <input type="email" class="form-control" id="user-email" placeholder="name@example.com" required />
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
      <button type="submit" class="btn btn-primary w-full mt-2">Add User</button>
    </form>
  </div>

  <div class="table-container">
    <h2 class="text-center text-purple-700">User Table</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="user-table-body">
        <!-- User data will be populated here -->
      </tbody>
    </table>
  </div>

  <script>
    const userForm = document.getElementById('user-form');
    const userTableBody = document.getElementById('user-table-body');

    // Fetch user data from the API
    function fetchUsers() {
      fetch('/api/users')
        .then(response => response.json())
        .then(data => {
          userTableBody.innerHTML = ''; // Clear existing rows
          data.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
              <td>${user.id}</td>
              <td>${user.name}</td>
              <td>${user.email}</td>
              <td>${user.role}</td>
              <td>
                <button class="btn btn-warning btn-sm" onclick="editUser(${user.id})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
              </td>
            `;
            userTableBody.appendChild(row);
          });
        })
        .catch(error => {
          console.error('Error fetching user data:', error);
        });
    }

    // Add new user
    userForm.addEventListener('submit', e => {
      e.preventDefault();
      const name = document.getElementById('user-name').value;
      const email = document.getElementById('user-email').value;
      const password = document.getElementById('user-password').value;
      const role = document.getElementById('user-role').value;

      fetch('/api/users', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ name, email, password, role }),
      })
      .then(response => response.json())
      .then(data => {
        alert(data.message);
        fetchUsers(); // Refresh the user table
        userForm.reset(); // Reset the form
      })
      .catch(error => {
        console.error('Error adding user:', error);
      });
    });

    // Edit user
    function editUser(userId) {
      fetch(`/api/users/${userId}`)
        .then(response => response.json())
        .then(user => {
          document.getElementById('user-name').value = user.name;
          document.getElementById('user-email').value = user.email;
          document.getElementById('user-password').value = ''; // Clear password field
          document.getElementById('user-role').value = user.role;

          // Change the form submission to update the user
          userForm.onsubmit = function(e) {
            e.preventDefault();
            updateUser(userId);
          };
        })
        .catch(error => {
          console.error('Error fetching user data:', error);
        });
    }

    // Update user
    function updateUser(userId) {
      const name = document.getElementById('user-name').value;
      const email = document.getElementById('user-email').value;
      const password = document.getElementById('user-password').value; // Password can be updated
      const role = document.getElementById('user-role').value;

      fetch(`/api/users/${userId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ name, email, password, role }),
      })
      .then(response => response.json())
      .then(data => {
        alert(data.message);
        fetchUsers(); // Refresh the user table
        userForm.reset(); // Reset the form
        userForm.onsubmit = function(e) {
          e.preventDefault();
          addUser();
        };
      })
      .catch(error => {
        console.error('Error updating user:', error);
      });
    }

    // Delete user
    function deleteUser(userId) {
      if (confirm('Are you sure you want to delete this user?')) {
        fetch(`/api/users/${userId}`, {
          method: 'DELETE',
        })
        .then(response => response.json())
        .then(data => {
          alert(data.message);
          fetchUsers(); // Refresh the user table
        })
        .catch(error => {
          console.error('Error deleting user:', error);
        });
      }
    }

    // Initial fetch of users
    fetchUsers();
  </script>
</body>
</html>
