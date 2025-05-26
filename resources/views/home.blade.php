<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-5">
        <div class="absolute top-0 right-0 p-4">
            <a href="welcome" class="btn btn-danger">Logout</a>
        </div>

        <div class="flex flex-col items-center justify-center min-h-screen">
            <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
                <h1 class="text-2xl font-bold text-center text-blue-600">Admin Dashboard</h1>
                <p class="mt-4 text-center text-gray-700">Kelola Peminjaman dan Laporan di Sini</p>
                
                <div class="mt-6">
                    <a href="peminjaman" class="btn btn-primary w-full mb-2">Kelola Peminjaman</a>
                    <a href="check-status" class="btn btn-secondary w-full mb-2">Cek Status Peminjaman</a>
                    <a href="approve-loans" class="btn btn-success w-full mb-2">Approval Peminjaman</a>
                    <a href="" class="btn btn-info w-full mb-2">Laporan Peminjaman</a>
                    <a href="users" class="btn btn-warning w-full">Kelola Akun Pengguna</a> 
                </div>
            </div>
        </div>
    </div>

</body>
</html>
