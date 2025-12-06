# 📚 API Documentation - ShopSmart

## 🚀 Mejoras Implementadas

### ✅ 1. Arquitectura RESTful
- **Separación de rutas**: Rutas API separadas en `routes/api.php`
- **Versionado**: API v1 con prefijo `/api/v1`
- **Estructura organizada**: Controladores específicos para API en `app/Http/Controllers/Api`

### ✅ 2. API Resources
Respuestas consistentes y transformadas usando Laravel API Resources:
- `ProductResource`: Transformación de productos
- `CategoryResource`: Transformación de categorías
- `OrderResource`: Transformación de órdenes
- `UserResource`: Transformación de usuarios
- `ReviewResource`: Transformación de reseñas

### ✅ 3. Manejo de Errores Centralizado
Respuestas de error consistentes en formato JSON:
```json
{
  "success": false,
  "message": "Error message",
  "errors": {}
}
```

Códigos de estado HTTP apropiados:
- `200`: Success
- `201`: Created
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `422`: Validation Error
- `500`: Server Error

### ✅ 4. Rate Limiting
Limitación de tasa implementada:
- **API Pública**: 60 requests/minuto
- **API Autenticada**: 100 requests/minuto
- **Admin API**: 150 requests/minuto
- **AI Endpoints**: 20 requests/minuto (más restrictivo)

### ✅ 5. Validación Robusta
- Validación de entrada en todos los endpoints
- Mensajes de error descriptivos
- Validación de tipos de archivo
- Validación de permisos

### ✅ 6. Optimización de Queries
Scopes añadidos al modelo Product:
- `active()`: Productos activos
- `inStock()`: Productos con stock
- `available()`: Productos activos y con stock
- `search($term)`: Búsqueda por nombre/descripción
- `inCategory($id)`: Filtrar por categoría
- `priceRange($min, $max)`: Filtrar por rango de precios
- `byVendor($userId)`: Productos de un vendedor
- `withRatings()`: Incluir calificaciones

### ✅ 7. Caché
- Caché de productos (5 minutos)
- Caché de categorías (1 hora)
- Invalidación automática al crear/actualizar/eliminar

---

## 🔌 Endpoints de la API

### 📦 Productos

#### GET `/api/v1/products`
Lista pública de productos con filtros

**Query Parameters:**
- `search`: Búsqueda por nombre/descripción
- `category`: ID de categoría
- `min_price`: Precio mínimo
- `max_price`: Precio máximo
- `sort`: `best_sellers`, `name`, `price`, `created_at`
- `order`: `asc`, `desc`
- `per_page`: Items por página (max 100)

**Response:**
```json
{
  "success": true,
  "data": [...],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 12,
    "total": 60
  }
}
```

#### GET `/api/v1/products/{id}`
Detalles de un producto

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Product Name",
    "description": "...",
    "price": 99.99,
    "formatted_price": "S/ 99.99",
    "stock": 10,
    "in_stock": true,
    "is_available": true,
    "image": "http://...",
    "category": {...},
    "reviews": [...],
    "average_rating": 4.5
  }
}
```

### 📂 Categorías

#### GET `/api/v1/categories`
Lista todas las categorías

#### GET `/api/v1/categories/{id}`
Detalles de una categoría

#### GET `/api/v1/categories/{id}/products`
Productos de una categoría

### 🛒 Carrito (Autenticado)

#### GET `/api/v1/cart`
Obtener carrito actual

#### POST `/api/v1/cart/add/{id}`
Agregar producto al carrito

**Body:**
```json
{
  "quantity": 1
}
```

#### PATCH `/api/v1/cart/update/{id}`
Actualizar cantidad de un producto

**Body:**
```json
{
  "quantity": 3
}
```

#### DELETE `/api/v1/cart/remove/{id}`
Eliminar producto del carrito

#### DELETE `/api/v1/cart/clear`
Vaciar carrito completo

### 📋 Órdenes (Autenticado)

#### GET `/api/v1/orders`
Lista de órdenes del usuario

#### POST `/api/v1/orders`
Crear nueva orden

**Body:**
```json
{
  "shipping_address": "Av. Example 123",
  "payment_method": "transferencia",
  "notes": "Optional notes",
  "cart": [
    {
      "product_id": 1,
      "quantity": 2
    }
  ]
}
```

#### GET `/api/v1/orders/{id}`
Detalles de una orden

#### PATCH `/api/v1/orders/{id}/cancel`
Cancelar una orden

**Body:**
```json
{
  "cancellation_reason": "Changed my mind"
}
```

### ⭐ Reseñas (Autenticado)

#### POST `/api/v1/reviews/products/{productId}`
Crear reseña de un producto

**Body:**
```json
{
  "rating": 5,
  "comment": "Excellent product!"
}
```

#### PUT `/api/v1/reviews/{id}`
Actualizar reseña

#### DELETE `/api/v1/reviews/{id}`
Eliminar reseña

### 🤖 IA (Pública con rate limit)

#### POST `/api/v1/ai/chat`
Chat general con IA

**Body:**
```json
{
  "message": "¿Cuáles son los mejores productos?"
}
```

#### GET `/api/v1/ai/product/{id}`
Análisis de producto con IA

#### POST `/api/v1/ai/vision`
Análisis de imagen con IA

**Body:**
```json
{
  "image_url": "https://..."
}
```

### 🤖 Chatbot IA con Memoria (Autenticado)

El chatbot con memoria permite mantener conversaciones persistentes, donde la IA recuerda el contexto de mensajes anteriores y puede recomendar productos reales de la tienda.

#### POST `/api/v1/chat`
Enviar mensaje al chatbot

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "message": "¿Qué productos me recomiendas para gaming?",
  "conversation_id": null
}
```

| Campo | Tipo | Requerido | Descripción |
|-------|------|-----------|-------------|
| message | string | Sí | Mensaje del usuario (máx 2000 caracteres) |
| conversation_id | integer | No | ID de conversación existente para continuar |

**Response (200):**
```json
{
  "success": true,
  "data": {
    "conversation_id": 1,
    "message": "Para gaming te recomiendo los siguientes productos: ..."
  }
}
```

**Response (422 - Validation Error):**
```json
{
  "message": "The message field is required.",
  "errors": {
    "message": ["The message field is required."]
  }
}
```

#### GET `/api/v1/chat/conversations`
Listar conversaciones del usuario

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "¿Qué productos me recomiendas...",
      "created_at": "2025-12-06T12:00:00.000000Z",
      "updated_at": "2025-12-06T12:05:00.000000Z"
    }
  ]
}
```

#### GET `/api/v1/chat/conversations/{conversationId}`
Obtener historial de una conversación

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "conversation": {
      "id": 1,
      "title": "¿Qué productos me recomiendas...",
      "created_at": "2025-12-06T12:00:00.000000Z"
    },
    "messages": [
      {
        "id": 1,
        "role": "user",
        "content": "¿Qué productos me recomiendas para gaming?",
        "created_at": "2025-12-06T12:00:00.000000Z"
      },
      {
        "id": 2,
        "role": "assistant",
        "content": "Para gaming te recomiendo...",
        "created_at": "2025-12-06T12:00:01.000000Z"
      }
    ]
  }
}
```

**Response (404):**
```json
{
  "message": "No query results for model [App\\Models\\Conversation]."
}
```

### 🟠 Vendedor (Autenticado + Role)

#### GET `/api/v1/vendor/products`
Lista de productos del vendedor

#### POST `/api/v1/vendor/products`
Crear nuevo producto

#### GET `/api/v1/vendor/products/{id}`
Ver producto específico

#### PUT `/api/v1/vendor/products/{id}`
Actualizar producto

#### DELETE `/api/v1/vendor/products/{id}`
Eliminar producto

#### GET `/api/v1/vendor/orders`
Órdenes con productos del vendedor

#### PATCH `/api/v1/vendor/orders/{id}/status`
Actualizar estado de orden

### 🔴 Admin (Autenticado + Role Admin)

Todos los endpoints del vendedor más:

#### GET `/api/v1/admin/products`
Todos los productos (cualquier vendedor)

#### GET `/api/v1/admin/orders`
Todas las órdenes

#### GET `/api/v1/admin/users`
Lista de usuarios

#### PATCH `/api/v1/admin/users/{id}/role`
Cambiar rol de usuario

#### POST `/api/v1/admin/users/{id}/suspend`
Suspender usuario

#### POST `/api/v1/admin/users/{id}/unsuspend`
Reactivar usuario

---

## 🔐 Autenticación

Para usar endpoints autenticados, necesitas:

### Opción 1: Laravel Sanctum (Recomendado para APIs)
1. Instalar Sanctum: `composer require laravel/sanctum`
2. Publicar configuración: `php artisan vendor:publish --provider="Laravel\Sanctum\ServiceProvider"`
3. Migrar: `php artisan migrate`
4. Crear token para usuario:
```php
$token = $user->createToken('api-token')->plainTextToken;
```
5. Usar token en headers:
```
Authorization: Bearer {token}
```

### Opción 2: Session-based (Web)
Usar las credenciales de sesión de Laravel estándar.

---

## 🛡️ Seguridad Implementada

1. **Rate Limiting**: Protección contra abuso de API
2. **Validación de entrada**: Todos los inputs validados
3. **Sanitización**: Prevención de XSS
4. **CORS**: Configuración adecuada de CORS
5. **Autenticación**: Middleware de autenticación
6. **Autorización**: Verificación de permisos por rol
7. **Encriptación**: Comunicación HTTPS (producción)

---

## 📊 Optimizaciones

1. **Caché**: Reducción de queries a DB
2. **Eager Loading**: Prevención de N+1 queries
3. **Paginación**: Limitación de resultados
4. **Índices de DB**: Consultas optimizadas
5. **Query Scopes**: Queries reutilizables y eficientes

---

## 🧪 Testing

### Ejemplos con cURL:

```bash
# Listar productos
curl -X GET "http://localhost/api/v1/products?per_page=5"

# Obtener producto específico
curl -X GET "http://localhost/api/v1/products/1"

# Buscar productos
curl -X GET "http://localhost/api/v1/products?search=laptop&category=1"

# Chat con IA (público)
curl -X POST "http://localhost/api/v1/ai/chat" \
  -H "Content-Type: application/json" \
  -d '{"message": "¿Qué productos recomiendas?"}'

# Chatbot con memoria (autenticado)
curl -X POST "http://localhost/api/v1/chat" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"message": "Hola, busco productos para gaming"}'

# Continuar conversación existente
curl -X POST "http://localhost/api/v1/chat" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"message": "¿Y cuál tiene mejor precio?", "conversation_id": 1}'

# Listar mis conversaciones
curl -X GET "http://localhost/api/v1/chat/conversations" \
  -H "Authorization: Bearer {token}"

# Ver historial de una conversación
curl -X GET "http://localhost/api/v1/chat/conversations/1" \
  -H "Authorization: Bearer {token}"

# Agregar al carrito (autenticado)
curl -X POST "http://localhost/api/v1/cart/add/1" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"quantity": 2}'

# Crear orden (autenticado)
curl -X POST "http://localhost/api/v1/orders" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "shipping_address": "Av. Test 123",
    "payment_method": "transferencia",
    "cart": [
      {"product_id": 1, "quantity": 2}
    ]
  }'
```

---

## 📝 Mejoras Futuras Sugeridas

- [ ] Autenticación OAuth2
- [ ] Webhooks para eventos
- [ ] GraphQL endpoint alternativo
- [ ] Documentación Swagger/OpenAPI
- [ ] Métricas y analytics de API
- [ ] Versionado más avanzado (v2, v3)
- [ ] WebSockets para updates en tiempo real
- [ ] Sistema de notificaciones push
- [ ] Export/Import de datos en bulk
- [ ] SDK/Client libraries en diferentes lenguajes

---

## 🔧 Configuración

### Variables de entorno necesarias:
```env
# OpenAI API (para funcionalidades de IA públicas)
OPENAI_API_KEY=your-api-key-here

# OpenRouter API (para chatbot con memoria)
OPENROUTER_API_KEY=your-openrouter-api-key
AI_MODEL=openai/gpt-3.5-turbo

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Laravel Sanctum (opcional pero recomendado)
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
```

---

## 📧 Soporte

Para reportar problemas o sugerir mejoras, contacta al equipo de desarrollo.

**Versión**: 1.0.0
**Última actualización**: Diciembre 2025
