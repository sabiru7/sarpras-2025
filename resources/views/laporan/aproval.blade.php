<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sistem Approval Peminjaman</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Custom scrollbar for table */
    tbody::-webkit-scrollbar {
      height: 8px;
    }
    tbody::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    tbody::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 4px;
    }
    tbody::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
    /* Tailwind button hover overrides */
  </style>
</head>
<body class="bg-gradient-to-r from-blue-50 to-indigo-100 min-h-screen font-sans selection:bg-indigo-400 selection:text-white">

  <header class="sticky top-0 z-50 bg-gradient-to-r from-indigo-700 via-purple-700 to-indigo-900 shadow-lg">
    <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
      <div class="text-white font-extrabold text-xl tracking-wide select-none">
        <span class="italic">Sistem Approval</span> <span class="uppercase">Peminjaman</span>
      </div>
    </nav>
  </header>

  <main class="container max-w-6xl mx-auto px-6 py-10">
    <!-- Form Section -->
    <section class="bg-white shadow-xl rounded-2xl p-8 mb-10 max-w-3xl mx-auto border border-indigo-200">
      <h1 class="text-indigo-900 text-3xl font-semibold mb-6 text-center drop-shadow-sm">Ajukan Peminjaman Baru</h1>
      <form id="loanRequestForm" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label for="requesterName" class="block mb-2 font-medium text-indigo-800 select-none">Nama Pemohon</label>
            <input
              type="text"
              id="requesterName"
              name="requesterName"
              required
              placeholder="Masukkan nama..."
              class="w-full rounded-lg border border-indigo-300 focus:ring-indigo-400 focus:border-indigo-500 px-4 py-3 shadow-sm placeholder:text-indigo-300 transition"
            />
          </div>
          <div>
            <label for="loanItem" class="block mb-2 font-medium text-indigo-800 select-none">Item Peminjaman</label>
            <input
              type="text"
              id="loanItem"
              name="loanItem"
              required
              placeholder="Nama barang..."
              class="w-full rounded-lg border border-indigo-300 focus:ring-indigo-400 focus:border-indigo-500 px-4 py-3 shadow-sm placeholder:text-indigo-300 transition"
            />
          </div>
          <div>
            <label for="loanDuration" class="block mb-2 font-medium text-indigo-800 select-none">Durasi (hari)</label>
            <input
              type="number"
              id="loanDuration"
              name="loanDuration"
              required
              min="1"
              placeholder="Contoh: 5"
              class="w-full rounded-lg border border-indigo-300 focus:ring-indigo-400 focus:border-indigo-500 px-4 py-3 shadow-sm placeholder:text-indigo-300 transition"
            />
          </div>
        </div>
        <div class="text-center">
          <button
            type="submit"
            class="inline-block bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold text-lg rounded-xl px-10 py-3 shadow-lg transition duration-300 shadow-indigo-400/60 select-none focus:outline-none focus:ring-4 focus:ring-indigo-300"
          >
            Ajukan Peminjaman
          </button>
        </div>
      </form>
    </section>

    <!-- Requests Table Section -->
    <section class="bg-white shadow-xl rounded-2xl border border-indigo-200 p-6 max-w-7xl mx-auto">
      <h2 class="text-indigo-900 text-2xl font-semibold mb-4 text-center drop-shadow-sm">Daftar Permintaan Peminjaman</h2>
      <div class="overflow-x-auto rounded-lg">
        <table class="min-w-full text-center divide-y divide-indigo-200">
          <thead class="bg-indigo-100/70 text-indigo-900 font-semibold tracking-wide select-none">
            <tr>
              <th class="p-4">#</th>
              <th class="p-4">Nama Pemohon</th>
              <th class="p-4">Item</th>
              <th class="p-4">Durasi (hari)</th>
              <th class="p-4">Status</th>
              <th class="p-4">Aksi</th>
            </tr>
          </thead>
          <tbody id="requestsTableBody" class="divide-y divide-indigo-100 bg-white">
            <tr>
              <td colspan="6" class="py-10 text-indigo-400 italic font-medium">Belum ada permintaan peminjaman</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (() => {
      const form = document.getElementById('loanRequestForm');
      const tableBody = document.getElementById('requestsTableBody');

      let requests = [];

      function createStatusBadge(status) {
        switch (status) {
          case 'pending':
            return '<span class="inline-block px-3 py-1 rounded-full bg-yellow-300 text-yellow-900 font-semibold select-none">Pending</span>';
          case 'approved':
            return '<span class="inline-block px-3 py-1 rounded-full bg-green-400 text-green-900 font-semibold select-none">Disetujui</span>';
          case 'rejected':
            return '<span class="inline-block px-3 py-1 rounded-full bg-red-400 text-red-900 font-semibold select-none">Ditolak</span>';
          default:
            return '<span class="inline-block px-3 py-1 rounded-full bg-gray-300 text-gray-700 font-semibold select-none">Unknown</span>';
        }
      }

      function createActionButtons(status, index) {
        if (status === 'pending') {
          return `
            <button aria-label="Setujui permintaan" title="Setujui" class="btn-approve flex items-center gap-2 px-3 py-1.5 mx-1" data-action="approve" data-index="${index}">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Setujui
            </button>
            <button aria-label="Tolak permintaan" title="Tolak" class="btn-reject flex items-center gap-2 px-3 py-1.5 mx-1" data-action="reject" data-index="${index}">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Tolak
            </button>
          `;
        }
        return '<span class="italic text-indigo-600 select-none">-</span>';
      }

      function renderTable() {
        if (requests.length === 0) {
          tableBody.innerHTML = `
            <tr>
              <td colspan="6" class="py-10 text-indigo-400 italic font-medium select-none">Belum ada permintaan peminjaman</td>
            </tr>`;
          return;
        }

        tableBody.innerHTML = requests
          .map(
            (req, index) => `
            <tr class="hover:bg-indigo-50 transition">
              <td class="p-4 font-semibold text-indigo-700 select-text">${index + 1}</td>
              <td class="p-4 font-medium text-indigo-900 text-left select-text">${req.name}</td>
              <td class="p-4 text-indigo-700 select-text">${req.item}</td>
              <td class="p-4 text-indigo-700 select-text">${req.duration}</td>
              <td class="p-4">${createStatusBadge(req.status)}</td>
              <td class="p-4">${createActionButtons(req.status, index)}</td>
            </tr>`
          )
          .join('');
      }

      function handleAction(index, action) {
        if (!requests[index]) return;
        if (action === 'approve') {
          requests[index].status = 'approved';
        } else if (action === 'reject') {
          requests[index].status = 'rejected';
        }
        renderTable();
      }

      tableBody.addEventListener('click', (e) => {
        if (e.target.closest('button')) {
          const btn = e.target.closest('button');
          const index = parseInt(btn.getAttribute('data-index'));
          const action = btn.getAttribute('data-action');
          handleAction(index, action);
        }
      });

      form.addEventListener('submit', (e) => {
        e.preventDefault();
        const name = form.requesterName.value.trim();
        const item = form.loanItem.value.trim();
        const duration = Number(form.loanDuration.value);

        if (!name || !item || !duration || duration < 1) {
          alert('Mohon isi semua kolom dengan benar.');
          return;
        }

        requests.push({
          name,
          item,
          duration,
          status: 'pending',
        });

        form.reset();
        renderTable();
      });

      renderTable();
    })();
  </script>
</body>
</html>

