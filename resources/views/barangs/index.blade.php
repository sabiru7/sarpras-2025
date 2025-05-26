<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Peminjaman Barang</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
</head>
<body class="bg-gray-100 min-h-screen font-sans">

  <header class="bg-indigo-700 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
      <h1 class="text-3xl font-bold">Dashboard Peminjaman Barang</h1>
      <nav>
        <ul class="flex space-x-6 text-lg">
          <li><a href="#" class="hover:underline">Beranda</a></li>
          <li><a href="#" class="hover:underline">Daftar Barang</a></li>
          <li><a href="#" class="hover:underline">Pengaturan</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-6 py-10">
    <section>
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Barang yang Tersedia untuk Dipinjam</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <!-- Item 1: Proyektor -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
          <img 
            src="https://images.unsplash.com/photo-1603791440384-56cd371ee9a7?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=600&amp;q=80" 
            alt="Proyektor" 
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h3 class="text-xl font-semibold mb-2 text-indigo-700">Proyektor</h3>
            <p class="text-gray-600 mb-1">Stok: <span class="font-semibold">2</span></p>
            <p class="text-green-600 font-semibold mb-3">Tersedia</p>
            <button class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition" onclick="pinjamBarang('Proyektor')">Pinjam</button>
          </div>
        </div>

        <!-- Item 2: Spidol -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
          <img 
            src="https://images.unsplash.com/photo-1589927986089-358c8c8c8c8c?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=600&amp;q=80"
            alt="Spidol" 
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h3 class="text-xl font-semibold mb-2 text-indigo-700">Spidol</h3>
            <p class="text-gray-600 mb-1">Stok: <span class="font-semibold">5</span></p>
            <p class="text-green-600 font-semibold mb-3">Tersedia</p>
            <button class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition" onclick="pinjamBarang('Spidol')">Pinjam</button>
          </div>
        </div>

        <!-- Item 3: Kabel HDMI -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
          <img 
            src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=600&amp;q=80"
            alt="Kabel HDMI" 
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h3 class="text-xl font-semibold mb-2 text-indigo-700">Kabel HDMI</h3>
            <p class="text-gray-600 mb-1">Stok: <span class="font-semibold">4</span></p>
            <p class="text-green-600 font-semibold mb-3">Tersedia</p>
            <button class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition" onclick="pinjamBarang('Kabel HDMI')">Pinjam</button>
          </div>
        </div>

        <!-- Item 4: Laptop -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
          <img 
            src="https://images.unsplash.com/photo-1511988617509-a57c8a288659?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=600&amp;q=80" 
            alt="Laptop" 
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h3 class="text-xl font-semibold mb-2 text-indigo-700">Laptop</h3>
            <p class="text-gray-600 mb-1">Stok: <span class="font-semibold">3</span></p>
            <p class="text-green-600 font-semibold mb-3">Tersedia</p>
            <button class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition" onclick="pinjamBarang('Laptop')">Pinjam</button>
          </div>
        </div>

        <!-- Item 5: Penghapus -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
          <img 
            src="https://images.unsplash.com/photo-1529472119199-5a99a4c1d11f?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=600&amp;q=80" 
            alt="Penghapus" 
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h3 class="text-xl font-semibold mb-2 text-indigo-700">Penghapus</h3>
            <p class="text-gray-600 mb-1">Stok: <span class="font-semibold">10</span></p>
            <p class="text-green-600 font-semibold mb-3">Tersedia</p>
            <button class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition" onclick="pinjamBarang('Penghapus')">Pinjam</button>
          </div>
        </div>

        <!-- Item 6: Isi Ulang Spidol -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
          <img 
            src="https://images.unsplash.com/photo-1602773862742-3f3e1f1f86da?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=600&amp;q=80" 
            alt="Isi Ulang Spidol" 
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h3 class="text-xl font-semibold mb-2 text-indigo-700">Isi Ulang Spidol</h3>
            <p class="text-gray-600 mb-1">Stok: <span class="font-semibold">6</span></p>
            <p class="text-green-600 font-semibold mb-3">Tersedia</p>
            <button class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition" onclick="pinjamBarang('Isi Ulang Spidol')">Pinjam</button>
          </div>
        </div>

      </div>
    </section>
  </main>

  <footer class="bg-indigo-700 text-white text-center py-6 mt-12">
    &copy; 2024 Sistem Peminjaman Barang. All rights reserved.
  </footer>


  <script>
    function pinjamBarang(namaBarang) {
        $.ajax({
            url: '/api/barang/pinjam', // Update to your API endpoint
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                nama: namaBarang,
                // Include CSRF token if necessary
                // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }),
            success: function(response) {
                alert(response.message);
                // Optionally, refresh the page or update UI to reflect the new stock
                location.reload(); // Reloads the page to show updated stock
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan saat meminjam barang. Silakan coba lagi.';
                alert(errorMessage);
            }

        });
    }
  </script>

</body>
</html>

