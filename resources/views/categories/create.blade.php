@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('breadcrumb')
    Tambah Kategori
@endsection

@section('content')
    <div class="min-h-screen px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center mb-8">
                <a href="{{ route('categories.index') }}"
                    class="mr-4 text-blue-600 hover:text-blue-800 transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-800">
                    Tambah Kategori Baru
                </h1>
            </div>

            <div class="bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Kategori</h2>
                    </div>

                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-8">
                            <div class="relative">
                                <input type="text" name="name" id="name"
                                    class="w-full px-5 py-3 bg-gray-50 border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 peer transition-all duration-300"
                                    placeholder=" " required>
                                <label for="name"
                                    class="absolute left-5 top-3 text-gray-400 peer-focus:text-blue-500 peer-focus:-translate-y-5 peer-focus:scale-90 peer-focus:font-medium peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 transition-all duration-300 origin-left">
                                    Nama Kategori
                                </label>
                            </div>

                            @error('name')
                                <div class="mt-3 flex items-center text-sm text-red-600 bg-red-50 px-4 py-2 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('categories.index') }}"
                                class="px-6 py-3 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Simpan Kategori
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-gray-50 px-8 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-500 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pastikan nama kategori unik dan deskriptif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
