@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
                <p class="text-gray-500 text-sm mt-1">Welcome back, Admin! Here's your daily summary</p>
            </div>
            <div class="mt-3 md:mt-0">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full bg-primary-100 text-primary-600 text-sm font-medium">
                    <i class="far fa-calendar-alt mr-2 text-sm"></i>
                    {{ now()->format('l, F j, Y') }}
                </span>
            </div>
        </div>

        <!-- Quick Actions Section - Moved to top -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-xs border border-gray-100 p-4">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Quick Actions</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="{{ route('items.create') }}"
                        class="group flex items-center p-3 rounded-lg border border-gray-100 hover:border-primary-200 hover:bg-primary-50 transition-colors">
                        <div
                            class="flex-shrink-0 h-8 w-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition-colors">
                            <i class="fas fa-plus text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-800 group-hover:text-primary-600">Add New Item</p>
                            <p class="text-xs text-gray-500">Add to inventory</p>
                        </div>
                    </a>

                    <a href="{{ route('loans.index') }}"
                        class="group flex items-center p-3 rounded-lg border border-gray-100 hover:border-green-200 hover:bg-green-50 transition-colors">
                        <div
                            class="flex-shrink-0 h-8 w-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <i class="fas fa-arrow-up-from-bracket text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-800 group-hover:text-green-600">Process Loan</p>
                            <p class="text-xs text-gray-500">Manage loans</p>
                        </div>
                    </a>

                    <a href="{{ route('users.index') }}"
                        class="group flex items-center p-3 rounded-lg border border-gray-100 hover:border-purple-200 hover:bg-purple-50 transition-colors">
                        <div
                            class="flex-shrink-0 h-8 w-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <i class="fas fa-users-gear text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-800 group-hover:text-purple-600">Manage Users</p>
                            <p class="text-xs text-gray-500">User accounts</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Kategori Card -->
            <div class="bg-white rounded-lg shadow-xs border border-gray-100 p-4 hover:shadow-sm transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kategori</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $categoryCount }}</h3>
                        <div class="mt-1 flex items-center text-xs">
                            <span class="text-green-500 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>
                                <span>2.5% from last week</span>
                            </span>
                        </div>
                    </div>
                    <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                        <i class="fas fa-tags text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Barang Card -->
            <div class="bg-white rounded-lg shadow-xs border border-gray-100 p-4 hover:shadow-sm transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Barang</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $itemCount }}</h3>
                        <div class="mt-1 flex items-center text-xs">
                            <span class="text-green-500 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>
                                <span>5.3% from last week</span>
                            </span>
                        </div>
                    </div>
                    <div class="p-2 rounded-lg bg-blue-50 text-blue-600">
                        <i class="fas fa-boxes-stacked text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- User Card -->
            <div class="bg-white rounded-lg shadow-xs border border-gray-100 p-4 hover:shadow-sm transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Users</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $userCount }}</h3>
                        <div class="mt-1 flex items-center text-xs">
                            <span class="text-green-500 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>
                                <span>3.1% from last week</span>
                            </span>
                        </div>
                    </div>
                    <div class="p-2 rounded-lg bg-purple-50 text-purple-600">
                        <i class="fas fa-users-gear text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Peminjaman Card -->
            <div class="bg-white rounded-lg shadow-xs border border-gray-100 p-4 hover:shadow-sm transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Peminjaman</p>
                        <h3 class="text-xl font-bold text-gray-800">{{ $loanCount }}</h3>
                        <div class="mt-1 flex items-center text-xs">
                            <span class="text-green-500 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>
                                <span>7.8% from last week</span>
                            </span>
                        </div>
                    </div>
                    <div class="p-2 rounded-lg bg-green-50 text-green-600">
                        <i class="fas fa-arrow-up-from-bracket text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section - Now full width -->
        <div class="bg-white rounded-lg shadow-xs border border-gray-100">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Recent Activity</h2>
                <a href="#" class="text-xs text-primary-600 hover:text-primary-800">View All</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($activities as $activity)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @php
                                    $iconColors = [
                                        'created' => 'bg-green-50 text-green-600',
                                        'updated' => 'bg-blue-50 text-blue-600',
                                        'deleted' => 'bg-red-50 text-red-600',
                                        'login' => 'bg-yellow-50 text-yellow-600',
                                        'logout' => 'bg-indigo-50 text-indigo-600',
                                        'default' => 'bg-gray-50 text-gray-600',
                                    ];
                                    $icon = match (true) {
                                        str_contains(strtolower($activity->action), 'create') => 'fa-plus-circle',
                                        str_contains(strtolower($activity->action), 'update') => 'fa-pen-circle',
                                        str_contains(strtolower($activity->action), 'delete') => 'fa-trash-can',
                                        str_contains(strtolower($activity->action), 'login') => 'fa-right-to-bracket',
                                        str_contains(strtolower($activity->action), 'logout')
                                            => 'fa-right-from-bracket',
                                        default => 'fa-info-circle',
                                    };
                                    $color = match (true) {
                                        str_contains(strtolower($activity->action), 'create') => $iconColors['created'],
                                        str_contains(strtolower($activity->action), 'update') => $iconColors['updated'],
                                        str_contains(strtolower($activity->action), 'delete') => $iconColors['deleted'],
                                        str_contains(strtolower($activity->action), 'login') => $iconColors['login'],
                                        str_contains(strtolower($activity->action), 'logout') => $iconColors['logout'],
                                        default => $iconColors['default'],
                                    };
                                @endphp
                                <div class="h-8 w-8 rounded-full {{ $color }} flex items-center justify-center">
                                    <i class="fas {{ $icon }} text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">
                                    {{ $activity->user->name }}
                                    <span class="capitalize">{{ strtolower($activity->action) }}</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity->log }}</p>
                                <div class="flex items-center mt-1 text-xs text-gray-400">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $activity->created_at->diffForHumans() }}
                                    <span class="mx-1">•</span>
                                    <i class="fas fa-network-wired mr-1"></i>
                                    {{ $activity->ip_address }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center">
                        <i class="fas fa-clipboard-list text-2xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500 text-sm">No activities found</p>
                    </div>
                @endforelse
            </div>
            @if ($activities->hasPages())
                <div class="px-4 py-2 border-t border-gray-100 bg-gray-50">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
