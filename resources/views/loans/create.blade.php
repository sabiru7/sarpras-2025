@extends('layouts.app')

@section('title', 'Pengajuan Peminjaman Barang')

@section('content')
    <div class=" py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Form Pengajuan Peminjaman</h2>
                <p class="text-sm text-gray-600 mt-1">Isi formulir berikut untuk mengajukan peminjaman barang</p>
            </div>

            <div class="p-6">
                <form action="{{ route('loans.store') }}" method="POST">
                    @csrf

                    <!-- Barang Field -->
                    <div class="mb-6">
                        <label for="item_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Barang <span class="text-red-500">*</span>
                        </label>
                        <select id="item_id" name="item_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('item_id') border-red-500 @enderror">
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} (Tersedia: {{ $item->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity Field -->
                    <div class="mb-6">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity') }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('quantity') border-red-500 @enderror">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Borrow Date Field -->
                    <div class="mb-6">
                        <label for="borrowed_at" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Pinjam <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="borrowed_at" name="borrowed_at" value="{{ old('borrowed_at') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('borrowed_at') border-red-500 @enderror">
                        @error('borrowed_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Return Date Field -->
                    <div class="mb-8">
                        <label for="returned_at" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Kembali <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="returned_at" name="returned_at" value="{{ old('returned_at') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('returned_at') border-red-500 @enderror">
                        @error('returned_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between">
                        <a href="{{ url()->previous() }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Set minimum dates for date inputs
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('borrow_date').min = today;

            document.getElementById('borrow_date').addEventListener('change', function() {
                document.getElementById('return_date').min = this.value;
            });
        });
    </script>
@endsection
