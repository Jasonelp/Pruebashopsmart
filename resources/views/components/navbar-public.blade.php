<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-8">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <span class="text-2xl font-bold text-white">ShopSmart <span class="text-blue-500">IA</span></span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition">Inicio</a>
                    <a href="{{ route('products.public.index') }}" class="text-gray-300 hover:text-white transition">Productos</a>
                    <a href="{{ route('categories.public.index') }}" class="text-gray-300 hover:text-white transition">Categorías</a>
                </div>
            </div>

            <!-- Search Bar (Desktop) -->
            <div class="hidden md:block flex-1 max-w-md mx-8">
                <form action="{{ route('products.public.index') }}" method="GET" class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Buscar productos..." 
                        class="w-full bg-gray-800 text-gray-100 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Right Side Icons -->
            <div class="flex items-center space-x-4">
                <!-- Cart Icon -->
                <a href="#" class="relative text-gray-300 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                </a>

                @auth
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ userOpen: false }">
                        <button @click="userOpen = !userOpen" class="flex items-center space-x-2 text-gray-300 hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="hidden md:block">{{ Auth::user()->name }}</span>
                        </button>

                        <div x-show="userOpen" @click.away="userOpen = false" class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-lg py-2 z-50">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700">Dashboard</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700">Mi Perfil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-300 hover:bg-gray-700">Cerrar Sesión</button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Login/Register Buttons -->
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">Registrarse</a>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="md:hidden text-gray-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" class="md:hidden bg-gray-800 border-t border-gray-700">
        <div class="px-4 py-3 space-y-3">
            <!-- Mobile Search -->
            <form action="{{ route('products.public.index') }}" method="GET">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Buscar productos..." 
                    class="w-full bg-gray-700 text-gray-100 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </form>

            <a href="{{ route('home') }}" class="block text-gray-300 hover:text-white">Inicio</a>
            <a href="{{ route('products.public.index') }}" class="block text-gray-300 hover:text-white">Productos</a>
            <a href="{{ route('categories.public.index') }}" class="block text-gray-300 hover:text-white">Categorías</a>

            @guest
                <a href="{{ route('login') }}" class="block text-gray-300 hover:text-white">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="block text-gray-300 hover:text-white">Registrarse</a>
            @endguest
        </div>
    </div>
</nav>