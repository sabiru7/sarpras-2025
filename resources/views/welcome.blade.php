<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login / Register</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(135deg, #667eea, #764ba2);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(118, 75, 162, 0.3);
    }
    .form-control:focus, .form-select:focus {
      border-color: #764ba2;
      box-shadow: 0 0 0 0.25rem rgba(118, 75, 162, 0.25);
    }
    .btn-primary {
      background: #764ba2;
      border: none;
      transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
      background: #5a3e85;
    }
    .link-primary {
      cursor: pointer;
      color: #764ba2;
      font-weight: 600;
    }
    .link-primary:hover {
      text-decoration: underline;
      color: #5a3e85;
    }
  </style>
</head>
<body class="flex items-center justify-center p-6">
  <div class="card bg-white p-5 max-w-md w-full">
    <h2 class="text-3xl font-bold mb-4 text-center text-purple-700">Sign In</h2>
    <form id="login-form" class="space-y-3">
      <div>
        <label for="login-email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="login-email" placeholder="name@example.com" required />
      </div>
      <div>
        <label for="login-password" class="form-label">Password</label>
        <input type="password" class="form-control" id="login-password" placeholder="Password" required />
      </div>
      <button type="submit" class="btn btn-primary w-full mt-2">Login</button>
    </form>

    <form id="register-form" class="space-y-3 hidden">
      <h2 class="text-3xl font-bold mb-4 text-center text-purple-700">Sign Up</h2>
      <div>
        <label for="register-role" class="form-label">Role</label>
        <select class="form-select" id="register-role" required>
          <option value="" disabled selected>Select role</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div>
        <label for="register-email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="register-email" placeholder="name@example.com" required />
      </div>
      <div>
        <label for="register-password" class="form-label">Password</label>
        <input type="password" class="form-control" id="register-password" placeholder="Password" required />
      </div>
      <button type="submit" class="btn btn-primary w-full mt-2">Register</button>
    </form>

    <p class="mt-4 text-center text-gray-600">
      <span id="toggle-text">Don't have an account? </span>
      <span id="toggle-link" class="link-primary">Sign up</span>
    </p>
  </div>

 <script>
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const toggleText = document.getElementById('toggle-text');
    const toggleLink = document.getElementById('toggle-link');

    toggleLink.addEventListener('click', () => {
        if (loginForm.classList.contains('hidden')) {
            // Show login form
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            toggleText.textContent = "Don't have an account? ";
            toggleLink.textContent = "Sign up";
        } else {
            // Show register form
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            toggleText.textContent = "Already have an account? ";
            toggleLink.textContent = "Sign in";
        }
    });

    loginForm.addEventListener('submit', e => {
        e.preventDefault();
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;

        fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Login failed'); // Handle non-200 responses
            }
            return response.json();
        })
        .then(data => {
            if (data.message) {
                alert(data.message);
                window.location.href = 'home'; // Redirect to the next page
            } else {
                alert("Login failed: " + JSON.stringify(data));
            }
        })
        .catch(error => {
            alert(error.message); // Show error message
            console.error('Error:', error);
        });
    });

    registerForm.addEventListener('submit', e => {
        e.preventDefault();
        const role = document.getElementById('register-role').value;
        const email = document.getElementById('register-email').value;
        const password = document.getElementById('register-password').value;

        fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name: '', email, password, role }), // Add name as needed
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                // Optionally redirect to login or next page
                window.location.href = 'home'; // Redirect to the next page
            } else {
                alert("Registration failed: " + JSON.stringify(data));
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
</body>
</html>
