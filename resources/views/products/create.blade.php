<x-app-layout>
    <div class="py-8 bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Encabezado -->
            <div class="mb-8">
                <a href="{{ route('categories.index') }}" class="text-gray-400 hover:text-white transition flex items-center mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver a Categorias
                </a>
                <h2 class="text-3xl font-bold text-white">Crear Nueva Categoria</h2>
                <p class="text-gray-400 mt-1">Completa los datos de la nueva categoria</p>
            </div>

            <!-- Formulario -->
            <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-8">
                <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">
                            Nombre de la Categoria <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full bg-gray-700 border border-gray-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="Ej: Smartphones, Laptops, Accesorios">
                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripcion -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-300 mb-2">
                            Descripcion
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full bg-gray-700 border border-gray-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition resize-none"
                                  placeholder="Descripcion breve de la categoria...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Imagen -->
                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-300 mb-2">
                            Imagen (Opcional)
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-600 border-dashed rounded-xl cursor-pointer bg-gray-700 hover:bg-gray-600 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="text-sm text-gray-400">Arrastra una imagen o haz clic para seleccionar</p>
                                </div>
                                <input type="file" name="image" id="image" class="hidden" accept="image/*">
                            </label>
                        </div>
                        @error('image')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-700">
                        <a href="{{ route('categories.index') }}" 
                           class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-xl shadow-lg transition">
                            Crear Categoria
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>