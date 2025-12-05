@extends('layouts.public')

@section('title', $product->name . ' - ShopSmart IA')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-gray-800 rounded-lg shadow-2xl overflow-hidden">
            <div class="md:flex">
                
                <!-- Imagen del producto -->
                <div class="md:w-1/2 bg-gray-700 flex items-center justify-center p-8">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="max-h-96 object-contain rounded-lg">
                    @else
                        <div class="text-gray-500 text-center">
                            <svg class="h-32 w-32 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-4 text-lg">Sin imagen disponible</p>
                        </div>
                    @endif
                </div>
                
                <!-- Detalles -->
                <div class="md:w-1/2 p-8 text-white">
                    
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-600 text-white">
                            {{ $product->category->name ?? 'Sin categoría' }}
                        </span>
                    </div>
                    
                    <h1 class="text-4xl font-bold mb-4">{{ $product->name }}</h1>
                    
                    @if($product->description)
                        <p class="text-gray-300 mb-6 text-lg leading-relaxed">{{ $product->description }}</p>
                    @else
                        <p class="text-gray-400 mb-6 italic">Este producto no tiene descripción disponible.</p>
                    @endif
                    
                    <!-- Especificaciones Técnicas -->
                    @if($product->specifications)
                        <div class="mb-6 bg-gray-700 rounded-lg p-4">
                            <h3 class="text-xl font-bold mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Especificaciones Técnicas
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($product->specifications as $key => $value)
                                    <div class="flex justify-between items-center border-b border-gray-600 pb-2">
                                        <span class="text-gray-400 font-medium">{{ ucfirst($key) }}:</span>
                                        <span class="text-white font-semibold">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-6">
                        <span class="text-5xl font-bold text-blue-400">
                            S/. {{ number_format($product->price, 2) }}
                        </span>
                    </div>
                    
                    <div class="mb-8">
                        @if($product->stock > 0)
                            <p class="text-green-400 font-semibold text-lg flex items-center">
                                ✔ En stock ({{ $product->stock }} disponibles)
                            </p>
                        @else
                            <p class="text-red-400 font-semibold text-lg flex items-center">
                                ✖ Producto agotado
                            </p>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        @auth
                            @if($product->stock > 0)
                                <a href="{{ route('add_to_cart', $product->id) }}" 
                                   class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg">
                                    🛒 Añadir al Carrito
                                </a>
                            @else
                                <button disabled class="w-full bg-gray-600 text-gray-400 font-bold py-4 px-6 rounded-lg">
                                    No Disponible
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg">
                                Inicia sesión para comprar
                            </a>
                        @endauth
                        
                        <a href="{{ route('products.public.index') }}" 
                           class="block w-full text-center bg-gray-700 hover:bg-gray-600 text-white font-semibold py-4 px-6 rounded-lg">
                            ← Volver a Productos
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Reseñas -->
        <div class="mt-8 bg-gray-800 rounded-lg shadow-2xl p-8">
            <h2 class="text-3xl font-bold text-white mb-6 flex items-center">
                ⭐ Reseñas de Clientes
                @if($reviewsCount > 0)
                    <span class="ml-3 text-xl text-gray-400">({{ $reviewsCount }})</span>
                @endif
            </h2>

            <!-- Promedio -->
            @if($reviewsCount > 0)
                <div class="mb-8 bg-gray-700 rounded-lg p-6 text-center">
                    <div class="text-5xl font-bold text-yellow-400">
                        {{ number_format($averageRating, 1) }}
                    </div>
                    <div class="flex justify-center mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-6 h-6 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-500' }}" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
            @endif

            <!-- Formulario de reseña -->
            @auth
                @if($canReview)
                    <div class="mb-8 bg-gray-700 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-white mb-4">Deja tu opinión</h3>

                        <form action="{{ route('reviews.store', $product->id) }}" method="POST" class="space-y-4">
                            @csrf

                            <!-- Calificación -->
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">Calificación</label>
                                <div class="flex space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                            <svg class="w-8 h-8 text-gray-500 peer-checked:text-yellow-400 hover:text-yellow-300" 
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Comentario -->
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">Comentario</label>
                                <textarea name="comment" rows="4" maxlength="500" 
                                          class="w-full bg-gray-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                                          placeholder="Escribe tu experiencia..."></textarea>
                            </div>

                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                                Publicar Reseña
                            </button>
                        </form>

                    </div>
                @endif
            @endauth

            <!-- Lista de reseñas -->
            @if($reviewsCount > 0)
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="bg-gray-700 rounded-lg p-6">
                            <div class="flex justify-between mb-3">
                                <div>
                                    <h4 class="text-white font-bold">{{ $review->user->name }}</h4>
                                    <div class="flex mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-500' }}" 
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>

                                <div class="text-gray-400 text-sm">
                                    {{ $review->created_at->diffForHumans() }}
                                </div>
                            </div>

                            @if($review->comment)
                                <p class="text-gray-300">{{ $review->comment }}</p>
                            @endif

                            @if(auth()->check() && (auth()->id() === $review->user_id || auth()->user()->role === 'admin'))
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm"
                                            onclick="return confirm('¿Eliminar esta reseña?')">
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>

            @else
                <p class="text-gray-400 text-center py-8">Aún no hay reseñas. ¡Sé el primero en opinar!</p>
            @endif
        </div>

    </div>
</div>
@endsection
