@extends('layouts.app')

@section('title', 'Daftar Pengembalian')

@section('breadcrumb')
    Manajemen Pengembalian
@endsection

@section('content')
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Daftar Pengembalian</h1>
                <p class="text-sm text-gray-500 mt-1">Daftar permohonan pengembalian barang</p>
            </div>
            <a href="{{ route('returns.export') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center text-sm font-medium transition-colors shadow-sm">
                <i class="fas fa-file-excel mr-2 text-sm"></i>
                Export Excel
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Status Filter -->
        <div class="mb-6 flex flex-wrap gap-2">
            <a href="{{ route('returns.index') }}"
                class="px-3 py-1 rounded-full text-sm font-medium {{ request()->get('status') === null ? 'bg-primary-100 text-primary-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                <i class="fas fa-list mr-1"></i> Semua
            </a>
            <a href="{{ route('returns.index', ['status' => 'pending']) }}"
                class="px-3 py-1 rounded-full text-sm font-medium {{ request()->get('status') === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                <i class="fas fa-clock mr-1"></i> Menunggu
            </a>
            <a href="{{ route('returns.index', ['status' => 'approved']) }}"
                class="px-3 py-1 rounded-full text-sm font-medium {{ request()->get('status') === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                <i class="fas fa-check-circle mr-1"></i> Disetujui
            </a>
            <a href="{{ route('returns.index', ['status' => 'rejected']) }}"
                class="px-3 py-1 rounded-full text-sm font-medium {{ request()->get('status') === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                <i class="fas fa-times-circle mr-1"></i> Ditolak
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            @if ($returns->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-user mr-1"></i> Peminjam
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-box mr-1"></i> Barang
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-hashtag mr-1"></i> Jumlah
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="far fa-calendar-check mr-1"></i> Tanggal Kembali
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-info-circle mr-1"></i> Status
                                </th>
                                <th scope="col"
                                    class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-cog mr-1"></i> Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($returns as $ret)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-800">
                                                    {{ $ret->loan->user->name ?? 'User dihapus' }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $ret->loan->user->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-800">{{ $ret->loan->item->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $ret->loan->item->category->name }}</div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2.5 py-1 inline-flex text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            {{ $ret->loan->quantity }} {{ $ret->loan->item->unit }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($ret->return_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if ($ret->status == 'pending')
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> Menunggu
                                            </span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if ($ret->status == 'pending')
                                            <div class="flex justify-end space-x-2">
                                                <form method="POST" action="{{ route('returns.approve', $ret->id) }}"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-green-600 hover:text-green-800 px-3 py-1.5 rounded-md inline-flex items-center transition-colors hover:bg-green-50">
                                                        <i class="fas fa-check mr-2 text-xs"></i> Setujui
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('returns.reject', $ret->id) }}"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 px-3 py-1.5 rounded-md inline-flex items-center transition-colors hover:bg-red-50">
                                                        <i class="fas fa-times mr-2 text-xs"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <a href="{{ route('returns.show', $ret) }}"
                                                class="text-primary-600 hover:text-primary-800 px-3 py-1.5 rounded-md inline-flex items-center transition-colors hover:bg-primary-50">
                                                <i class="fas fa-eye mr-2 text-xs"></i> Detail
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center">
                    <i class="fas fa-undo-alt text-3xl text-gray-300 mb-3"></i>
                    <h3 class="mt-2 text-sm font-medium text-gray-700">Tidak ada data pengembalian</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada permohonan pengembalian barang</p>
                </div>
            @endif
        </div>

        @if ($returns->hasPages())
            <div class="mt-6 px-2">
                {{ $returns->links() }}
            </div>
        @endif
    </div>
@endsection
