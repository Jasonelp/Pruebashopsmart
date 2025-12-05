<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Encabezado con Botón -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">
                    📂 Gestión de Categorías
                </h2>
                <a href="{{ route('categories.create') }}" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition transform hover:scale-105 flex items-center">
                    <span class="mr-2 text-xl">+</span> Nueva Categoría
                </a>
            </div>

            <!-- Mensajes de éxito -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-900/50 border border-green-500 text-green-200 rounded-lg flex items-center animate-fade-in-down">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tabla Oscura -->
            <div class="bg-gray-800 overflow-hidden shadow-2xl sm:rounded-xl border border-gray-700">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-gray-300">
                            <thead>
                                <tr class="border-b border-gray-600 text-sm uppercase tracking-wider bg-gray-700/30 text-gray-400">
                                    <th class="p-4 rounded-tl-lg">ID</th>
                                    <th class="p-4">Nombre</th>
                                    <th class="p-4">Descripción</th>
                                    <th class="p-4">Total Productos</th>
                                    <th class="p-4 text-center rounded-tr-lg">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse($categories as $cat)
                                <tr class="hover:bg-gray-700/50 transition duration-150 group">
                                    <td class="p-4 font-mono text-blue-400 font-bold">#{{ $cat->id }}</td>
                                    
                                    <td class="p-4 font-medium text-white flex items-center">
                                        <!-- Si tuvieras íconos, irían aquí -->
                                        <div class="h-8 w-8 rounded-full bg-gray-700 flex items-center justify-center mr-3 text-xs font-bold text-gray-400">
                                            {{ substr($cat->name, 0, 1) }}
                                        </div>
                                        {{ $cat->name }}
                                    </td>
                                    
                                    <td class="p-4 text-gray-400 text-sm italic">
                                        {{ Str::limit($cat->description, 50) ?? 'Sin descripción' }}
                                    </td>
                                    
                                    <td class="p-4">
                                        <span class="px-3 py-1 text-xs rounded-full bg-indigo-900 text-indigo-200 border border-indigo-700 font-bold">
                                            {{ $cat->products_count ?? 0 }} productos
                                        </span>
                                    </td>
                                    
                                    <td class="p-4 text-center">
                                        <div class="flex justify-center items-center space-x-3">
                                            <!-- Botón Editar -->
                                            <a href="{{ route('categories.edit', $cat->id) }}" class="text-blue-400 hover:text-blue-300 transition hover:scale-110" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            
                                            <!-- Botón Eliminar -->
                                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition hover:scale-110" onclick="return confirm('¿Estás seguro de eliminar la categoría {{ $cat->name }}?')" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p>No hay categorías registradas aún.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación (si la usas en el controlador) -->
                    @if(method_exists($categories, 'links'))
                        <div class="mt-4">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>