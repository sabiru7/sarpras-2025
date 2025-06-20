@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('breadcrumb')
    Manajemen Pengguna
@endsection

@section('content')
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Manajemen Pengguna</h1>
                <p class="text-sm text-gray-500 mt-1">Daftar seluruh pengguna sistem</p>
            </div>
            <a href="{{ route('users.create') }}"
                class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg inline-flex items-center text-sm font-medium transition-colors shadow-sm">
                <i class="fas fa-user-plus mr-2 text-sm"></i>
                Tambah Pengguna
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Role Filter -->
        <div class="mb-6 flex flex-wrap gap-2">
            <a href="{{ route('users.index') }}"
                class="px-3 py-1 rounded-full text-sm font-medium {{ request()->get('role') === null ? 'bg-primary-100 text-primary-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                <i class="fas fa-users mr-1"></i> Semua
            </a>
            <a href="{{ route('users.index', ['role' => 'admin']) }}"
                class="px-3 py-1 rounded-full text-sm font-medium {{ request()->get('role') === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                <i class="fas fa-user-shield mr-1"></i> Admin
            </a>
            <a href="{{ route('users.index', ['role' => 'user']) }}"
                class="px-3 py-1 rounded-full text-sm font-medium {{ request()->get('role') === 'user' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                <i class="fas fa-user mr-1"></i> User
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            @if ($users->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-user-circle mr-1"></i> Pengguna
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-cog mr-1"></i> Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-800">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <span
                                                        class="px-1.5 py-0.5 rounded-full {{ $user->is_admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                        <i
                                                            class="fas {{ $user->is_admin ? 'fa-user-shield' : 'fa-user' }} mr-1"></i>
                                                        {{ $user->is_admin ? 'Admin' : 'User' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-800">
                                            <i class="fas fa-envelope mr-1 text-gray-400"></i> {{ $user->email }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>
                                            Terdaftar: {{ $user->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('users.edit', $user) }}"
                                                class="text-primary-600 hover:text-primary-800 px-3 py-1.5 rounded-md inline-flex items-center transition-colors hover:bg-primary-50">
                                                <i class="fas fa-user-edit mr-2 text-xs"></i> Edit
                                            </a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this.form)"
                                                    class="text-red-600 hover:text-red-800 px-3 py-1.5 rounded-md inline-flex items-center transition-colors hover:bg-red-50">
                                                    <i class="fas fa-user-times mr-2 text-xs"></i> Hapus
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
                    <i class="fas fa-users-slash text-3xl text-gray-300 mb-3"></i>
                    <h3 class="mt-2 text-sm font-medium text-gray-700">Tidak ada data pengguna</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada pengguna terdaftar dalam sistem</p>
                    <div class="mt-6">
                        <a href="{{ route('users.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700">
                            <i class="fas fa-user-plus mr-2 text-sm"></i>
                            Tambah Pengguna
                        </a>
                    </div>
                </div>
            @endif
        </div>

        @if ($users->hasPages())
            <div class="mt-6 px-2">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <script>
        function confirmDelete(form) {
            if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
                form.submit();
            }
        }
    </script>
@endsection
