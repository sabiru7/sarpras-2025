@extends('layouts.app')

@section('title', 'Data Barang')

@section('breadcrumb')
    Manajemen Barang
@endsection

@section('content')
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Manajemen Barang</h1>
                <p class="text-sm text-gray-500 mt-1">Daftar seluruh barang yang tersedia</p>
            </div>
            <a href="{{ route('items.create') }}"
                class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg inline-flex items-center text-sm font-medium transition-colors shadow-sm">
                <i class="fas fa-box-circle-plus mr-2 text-sm"></i>
                Tambah Barang
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            @if ($items->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-box-open mr-1"></i> Nama Barang
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-1"></i> Kategori
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-layer-group mr-1"></i> Jumlah
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-image mr-1"></i> Foto
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-cog mr-1"></i> Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($items as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if ($item->photo)
                                                <img src="{{ asset('storage/' . $item->photo) }}" alt="Foto Barang"
                                                    class="h-8 w-8 rounded-md object-cover border border-gray-200 mr-3">
                                            @endif
                                            <div class="text-sm font-medium text-gray-800">{{ $item->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600 bg-gray-50 px-2.5 py-1 rounded-full">
                                            {{ $item->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2.5 py-1 inline-flex text-xs font-medium rounded-full
                                            {{ $item->quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $item->quantity }} {{ $item->unit }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if ($item->photo)
                                            <img src="{{ asset('storage/' . $item->photo) }}" alt="Foto Barang"
                                                class="h-10 w-10 rounded-md object-cover border border-gray-200 hover:shadow transition-shadow cursor-pointer"
                                                onclick="showImageModal('{{ asset('storage/' . $item->photo) }}')">
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('items.edit', $item) }}"
                                                class="text-primary-600 hover:text-primary-800 px-3 py-1.5 rounded-md inline-flex items-center transition-colors hover:bg-primary-50">
                                                <i class="fas fa-pen-square mr-2 text-xs"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('items.destroy', $item) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this.form)"
                                                    class="text-red-600 hover:text-red-800 px-3 py-1.5 rounded-md inline-flex items-center transition-colors hover:bg-red-50">
                                                    <i class="fas fa-trash-alt mr-2 text-xs"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center">
                    <i class="fas fa-boxes-alt text-3xl text-gray-300 mb-3"></i>
                    <h3 class="mt-2 text-sm font-medium text-gray-700">Tidak ada data barang</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan barang baru</p>
                    <div class="mt-6">
                        <a href="{{ route('items.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700">
                            <i class="fas fa-box-circle-plus mr-2 text-sm"></i>
                            Tambah Barang
                        </a>
                    </div>
                </div>
            @endif
        </div>

        @if ($items->hasPages())
            <div class="mt-6 px-2">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-75">
        <div class="relative max-w-4xl mx-auto">
            <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <img id="modalImage" src="" alt="Preview" class="max-h-[80vh] rounded-lg shadow-xl">
        </div>
    </div>

    <script>
        function confirmDelete(form) {
            if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
                form.submit();
            }
        }

        function showImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    </script>
@endsection
