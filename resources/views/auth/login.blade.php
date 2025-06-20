<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMK TARUNA BHAKTI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('images/bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <!-- Background overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>

    <!-- Login container -->
    <div class="login-container relative z-10 p-8 rounded-lg shadow-xl w-full max-w-md">
        <!-- School logo -->
        <div class="flex justify-center mb-4">
            <img src="images/tb.jpg"
                 alt="School Logo"
                 class="w-24 h-24">
        </div>

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">SMK TARUNA BHAKTI</h1>
            <p class="text-lg text-gray-600 mt-2">Selamat Datang</p>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form method="POST" action="/login" class="space-y-6">
            @csrf

            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2" for="email">Email</label>
                <input
                    type="text"
                    id="email"
                    name="email"
                    placeholder="Masukkan Email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-medium mb-2" for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan Password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>

            <div>
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150"
                >
                    Login
                </button>
            </div>
        </form>
    </div>
</body>

</html>
