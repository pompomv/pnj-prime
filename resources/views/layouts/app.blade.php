<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PNJ PRIME')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- ─── Navbar ─────────────────────────────────────────────────────────── --}}
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                {{-- Brand & Navigation Links --}}
                <div class="flex items-center space-x-6">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5">
                        <div class="w-9 h-9 bg-blue-800 rounded-xl flex items-center justify-center shadow-sm">
                            <span class="text-white font-extrabold text-sm tracking-tight">PNJ</span>
                        </div>
                        <span class="text-xl font-extrabold text-blue-900 tracking-tight">PNJ PRIME</span>
                    </a>

                    @auth
                    <div class="hidden md:flex items-center space-x-2">
                        <a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition">
                            Dashboard
                        </a>

                        @if(in_array(auth()->user()->role, ['dosen', 'mahasiswa']))
                            <a href="{{ route('inovasi.create') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('inovasi.create') ? 'bg-blue-50 text-blue-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition">
                                + Ajukan Inovasi
                            </a>
                        @endif

                        @if(auth()->user()->role === 'eksternal')
                            <a href="{{ route('marketplace.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('marketplace.*') ? 'bg-blue-50 text-blue-800' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition">
                                Katalog Etalase
                            </a>
                        @endif
                    </div>
                    @endauth
                </div>

                {{-- Right Side --}}
                @auth
                <div class="flex items-center space-x-3">
                    {{-- Role Badge --}}
                    @php
                        $roleBadgeColors = [
                            'dosen'     => 'bg-blue-100 text-blue-800',
                            'mahasiswa' => 'bg-green-100 text-green-800',
                            'p3m'       => 'bg-yellow-100 text-yellow-800',
                            'rtpu'      => 'bg-purple-100 text-purple-800',
                            'pimpinan'  => 'bg-red-100 text-red-800',
                            'eksternal' => 'bg-gray-100 text-gray-800',
                        ];
                        $badgeColor = $roleBadgeColors[auth()->user()->role] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $badgeColor }}">
                        {{ strtoupper(auth()->user()->role) }}
                    </span>

                    {{-- User Name --}}
                    <span class="text-sm text-gray-800 font-semibold hidden sm:block">
                        {{ auth()->user()->name }}
                    </span>

                    {{-- Logout --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-xs text-gray-500 hover:text-red-600 transition-colors px-3 py-1.5 rounded-lg hover:bg-red-50 font-medium">
                            Keluar
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ─── Flash Messages ─────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm shadow-sm">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="flex items-center p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm shadow-sm">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9v4a1 1 0 102 0V9a1 1 0 10-2 0zm0-4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-800 text-sm shadow-sm">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif

    {{-- ─── Main Content ────────────────────────────────────────────────────── --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full">
        @yield('content')
    </main>

    {{-- ─── Footer ──────────────────────────────────────────────────────────── --}}
    <footer class="mt-auto py-6 text-center text-xs text-gray-400 border-t border-gray-100 bg-white">
        &copy; {{ date('Y') }} Politeknik Negeri Jakarta — PNJ PRIME (Sistem E-Commerce &amp; Booking Jasa Kampus)
    </footer>

</body>
</html>
