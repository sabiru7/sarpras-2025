@extends('layouts.app')

@section('title', 'Kategori')

@section('breadcrumb')
    Manajemen Kategori
@endsection

@section('content')
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Manajemen Kategori</h1>
                <p class="text-sm text-gray-500 mt-1">Daftar kategori produk yang tersedia</p>
            </div>
            <a href="{{ route('categories.create') }}"
                class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg inline-flex items-center text-sm font-medium transition-colors shadow-sm">
                <i class="fas fa-plus mr-2 text-sm"></i>
                Tambah Kategori
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            @if ($categories->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Kategori
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-800">{{ $category->name }}</div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('categories.edit', $category) }}"
                                                class="text-primary-600 hover:text-primary-800 px-3 py-1.5 rounded-md inline-flex items-center transition-colors hover:bg-primary-50">
                                                <i class="fas fa-pen mr-2 text-xs"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                                    class="text-red-600 hover:text-red-800 px-3 py-1.5 rounded-md inline-flex items-center transition-colors hover:bg-red-50">
                                                    <i class="fas fa-trash mr-2 text-xs"></i>
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
                    <i class="fas fa-tags text-3xl text-gray-300 mb-3"></i>
                    <h3 class="mt-2 text-sm font-medium text-gray-700">Tidak ada kategori</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat kategori baru</p>
                    <div class="mt-6">
                        <a href="{{ route('categories.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700">
                            <i class="fas fa-plus mr-2 text-sm"></i>
                            Tambah Kategori
                        </a>
                    </div>
                </div>
            @endif
        </div>

        @if ($categories->hasPages())
            <div class="mt-6 px-2">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
