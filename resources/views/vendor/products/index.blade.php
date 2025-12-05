@extends('layouts.app-simple')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Mis Productos</h1>
                <p class="text-gray-300">Gestiona tu inventario de productos</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('vendor.dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition">
                    ← Dashboard
                </a>
                <button onclick="document.getElementById('modalAgregar').classList.remove('hidden')" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar Producto
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
        @endif

        <!-- Lista de Productos -->
        <div class="bg-white/10 backdrop-blur rounded-xl border border-white/20 overflow-hidden">
            <table class="w-full">
                <thead class="bg-white/5">
                    <tr class="text-gray-300 text-left">
                        <th class="px-6 py-4">Producto</th>
                        <th class="px-6 py-4">Categoría</th>
                        <th class="px-6 py-4">Precio</th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-b border-white/5 text-gray-200 hover:bg-white/5">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gray-700 rounded-lg mr-3 flex items-center justify-center overflow-hidden">
                                    @if($product->image)
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium">{{ $product->name }}</p>
                                    <p class="text-gray-400 text-sm">{{ Str::limit($product->description, 40) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-500/20 text-blue-300 px-2 py-1 rounded text-sm">
                                {{ $product->category->name ?? 'Sin categoría' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-green-400">S/ {{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="@if($product->stock > 10) text-green-400 @elseif($product->stock > 0) text-yellow-400 @else text-red-400 @endif">
                                {{ $product->stock }} unidades
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button onclick="editarProducto({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ addslashes($product->description) }}', {{ $product->price }}, {{ $product->stock }}, {{ $product->category_id }}, '{{ $product->image }}')" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                    Editar
                                </button>
                                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" 
                                      onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p class="text-gray-400 text-lg mb-2">No tienes productos aún</p>
                            <p class="text-gray-500 text-sm">Haz clic en "Agregar Producto" para empezar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>

    </div>
</div>

<!-- Modal Agregar Producto -->
<div id="modalAgregar" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4">
    <div class="bg-gray-800 rounded-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-white">Agregar Producto</h3>
                <button onclick="document.getElementById('modalAgregar').classList.add('hidden')" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('vendor.products.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-300 mb-2">Nombre del Producto *</label>
                        <input type="text" name="name" required 
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 mb-2">Descripción</label>
                        <textarea name="description" rows="3" 
                                  class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-300 mb-2">Precio (S/) *</label>
                            <input type="number" name="price" step="0.01" min="0" required 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 mb-2">Stock *</label>
                            <input type="number" name="stock" min="0" required 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-300 mb-2">Categoría *</label>
                        <select name="category_id" required 
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                            <option value="">Seleccionar categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-300 mb-2">URL de Imagen</label>
                        <input type="url" name="image" placeholder="https://ejemplo.com/imagen.jpg"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modalAgregar').classList.add('hidden')" 
                            class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg transition">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition">
                        Agregar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Producto -->
<div id="modalEditar" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4">
    <div class="bg-gray-800 rounded-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-white">Editar Producto</h3>
                <button onclick="document.getElementById('modalEditar').classList.add('hidden')" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="formEditar" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-300 mb-2">Nombre del Producto *</label>
                        <input type="text" name="name" id="edit_name" required 
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 mb-2">Descripción</label>
                        <textarea name="description" id="edit_description" rows="3" 
                                  class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-300 mb-2">Precio (S/) *</label>
                            <input type="number" name="price" id="edit_price" step="0.01" min="0" required 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 mb-2">Stock *</label>
                            <input type="number" name="stock" id="edit_stock" min="0" required 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-300 mb-2">Categoría *</label>
                        <select name="category_id" id="edit_category" required 
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-300 mb-2">URL de Imagen</label>
                        <input type="url" name="image" id="edit_image" placeholder="https://ejemplo.com/imagen.jpg"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modalEditar').classList.add('hidden')" 
                            class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg transition">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarProducto(id, name, description, price, stock, category_id, image) {
    document.getElementById('formEditar').action = '/vendedor/productos/' + id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_stock').value = stock;
    document.getElementById('edit_category').value = category_id;
    document.getElementById('edit_image').value = image || '';
    document.getElementById('modalEditar').classList.remove('hidden');
}
</script>
@endsection