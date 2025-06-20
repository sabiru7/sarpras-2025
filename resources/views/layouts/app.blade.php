<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sarpras Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    boxShadow: {
                        'soft': '0 4px 14px 0 rgba(0, 0, 0, 0.05)',
                        'soft-md': '0 6px 20px 0 rgba(0, 0, 0, 0.08)'
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* Sidebar transitions */
        .sidebar-item {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Enhanced active state */
        .sidebar-item.active {
            background: linear-gradient(90deg, rgba(14, 165, 233, 0.1) 0%, rgba(14, 165, 233, 0.05) 100%);
            color: #0ea5e9;
            font-weight: 500;
            position: relative;
        }

        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #0ea5e9;
            border-radius: 0 4px 4px 0;
        }

        .sidebar-item.active i {
            color: #0ea5e9;
            transform: scale(1.05);
        }

        /* Hover effect */
        .sidebar-item:hover:not(.active) {
            background-color: rgba(226, 232, 240, 0.4);
            transform: translateX(2px);
        }

        /* Header shadow */
        .header-shadow {
            box-shadow: 0 2px 10px -3px rgba(0, 0, 0, 0.05);
        }

        /* Modern scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Pulse animation for active nav */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.4);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(14, 165, 233, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0);
            }
        }

        .active-icon {
            animation: pulse 1.5s infinite;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex text-gray-800">

    <!-- Modern Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col fixed h-full z-10">
        <!-- Enhanced Logo Area -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-gray-100">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <div
                    class="h-9 w-9 rounded-lg bg-primary-600 flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
                    <i class="fas fa-cubes text-white text-sm"></i>
                </div>
                <div>
                    <span class="text-xl font-bold text-gray-800 tracking-tight">Sarpras</span>
                    <span class="block text-xs text-primary-600 font-medium">Administration</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <div class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <!-- Menu Section -->
            <div class="px-3 py-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Navigation</p>
            </div>

            <a href="{{ route('dashboard') }}"
                class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center p-3 text-gray-600 rounded-lg group">
                <div class="h-5 w-5 flex items-center justify-center relative">
                    <i
                        class="fas fa-tachometer-alt text-gray-400 group-hover:text-primary-500 {{ request()->routeIs('dashboard') ? 'active-icon' : '' }}"></i>
                </div>
                <span class="ml-3 font-medium text-sm">Dashboard</span>
                <i class="fas fa-chevron-right text-xs text-gray-400 ml-auto"></i>
            </a>

            <a href="{{ route('categories.index') }}"
                class="sidebar-item {{ request()->routeIs('categories.index') ? 'active' : '' }} flex items-center p-3 text-gray-600 rounded-lg group">
                <div class="h-5 w-5 flex items-center justify-center">
                    <i
                        class="fas fa-layer-group text-gray-400 group-hover:text-primary-500 {{ request()->routeIs('categories.index') ? 'active-icon' : '' }}"></i>
                </div>
                <span class="ml-3 font-medium text-sm">Categories</span>
                <i class="fas fa-chevron-right text-xs text-gray-400 ml-auto"></i>
            </a>

            <a href="{{ route('items.index') }}"
                class="sidebar-item {{ request()->routeIs('items.index') ? 'active' : '' }} flex items-center p-3 text-gray-600 rounded-lg group">
                <div class="h-5 w-5 flex items-center justify-center">
                    <i
                        class="fas fa-boxes-stacked text-gray-400 group-hover:text-primary-500 {{ request()->routeIs('items.index') ? 'active-icon' : '' }}"></i>
                </div>
                <span class="ml-3 font-medium text-sm">Inventory</span>
                <i class="fas fa-chevron-right text-xs text-gray-400 ml-auto"></i>
            </a>

            <a href="{{ route('loans.index') }}"
                class="sidebar-item {{ request()->routeIs('loans.index') ? 'active' : '' }} flex items-center p-3 text-gray-600 rounded-lg group">
                <div class="h-5 w-5 flex items-center justify-center">
                    <i
                        class="fas fa-arrow-up-from-bracket text-gray-400 group-hover:text-primary-500 {{ request()->routeIs('loans.index') ? 'active-icon' : '' }}"></i>
                </div>
                <span class="ml-3 font-medium text-sm">Loans</span>
                <i class="fas fa-chevron-right text-xs text-gray-400 ml-auto"></i>
            </a>

            <a href="{{ route('returns.index') }}"
                class="sidebar-item {{ request()->routeIs('returns.index') ? 'active' : '' }} flex items-center p-3 text-gray-600 rounded-lg group">
                <div class="h-5 w-5 flex items-center justify-center">
                    <i
                        class="fas fa-undo-alt text-gray-400 group-hover:text-primary-500 {{ request()->routeIs('returns.index') ? 'active-icon' : '' }}"></i>
                </div>
                <span class="ml-3 font-medium text-sm">Returns</span>
                <i class="fas fa-chevron-right text-xs text-gray-400 ml-auto"></i>
            </a>

            <a href="{{ route('users.index') }}"
                class="sidebar-item {{ request()->routeIs('users.index') ? 'active' : '' }} flex items-center p-3 text-gray-600 rounded-lg group">
                <div class="h-5 w-5 flex items-center justify-center">
                    <i
                        class="fas fa-users-gear text-gray-400 group-hover:text-primary-500 {{ request()->routeIs('users.index') ? 'active-icon' : '' }}"></i>
                </div>
                <span class="ml-3 font-medium text-sm">User Management</span>
                <i class="fas fa-chevron-right text-xs text-gray-400 ml-auto"></i>
            </a>

            <!-- System Section -->
            <div class="px-3 py-2 mt-8">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">System</p>
            </div>

            <form action="/logout" method="POST">
                @csrf
                <button type="submit"
                    class="w-full text-left sidebar-item flex items-center p-3 text-gray-600 rounded-lg hover:text-red-500 group">
                    <div class="h-5 w-5 flex items-center justify-center">
                        <i class="fas fa-right-from-bracket text-gray-400 group-hover:text-red-500"></i>
                    </div>
                    <span class="ml-3 font-medium text-sm">Logout</span>
                    <i class="fas fa-chevron-right text-xs text-gray-400 ml-auto"></i>
                </button>
            </form>
        </div>

        <!-- Enhanced User Profile -->
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center shadow-inner">
                    <i class="fas fa-user-shield text-primary-600 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">Administrator</p>
                    <p class="text-xs text-gray-500 truncate">admin@example.com</p>
                </div>
                <button class="p-1 rounded-full hover:bg-gray-200 transition-colors">
                    <i class="fas fa-ellipsis-vertical text-gray-500 text-sm"></i>
                </button>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 ml-64">
        <!-- Modern Header -->
        <header
            class="bg-white border-b border-gray-100 py-4 px-8 flex items-center justify-between sticky top-0 z-10 header-shadow">
            <div>
                <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard Overview')</h1>
                <nav class="flex text-sm mt-1">
                    <a href="{{ route('dashboard') }}" class="text-primary-600 hover:text-primary-800">Home</a>
                    <span class="mx-2 text-gray-400">/</span>
                    <span class="text-gray-600">@yield('breadcrumb', 'Dashboard')</span>
                </nav>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <button class="p-2 rounded-full hover:bg-gray-100 transition-colors relative">
                        <i class="fas fa-bell text-gray-500"></i>
                        <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Content Container -->
        <main class="p-6 max-w-7xl mx-auto">
            @yield('content')
        </main>
    </div>

</body>

</html>
