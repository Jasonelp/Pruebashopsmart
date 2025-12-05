# 🛒 ShopSmart IA

ShopSmart IA es una plataforma de e-commerce moderna desarrollada con Laravel que integra inteligencia artificial para mejorar la experiencia de compra. El sistema incluye roles de usuario (cliente, vendedor, administrador) y funcionalidades avanzadas de IA para recomendaciones y análisis de productos.

## 🚀 Características Principales

- **Sistema de Roles**: Cliente, Vendedor y Administrador
- **Catálogo de Productos**: Gestión completa de productos y categorías
- **Carrito de Compras**: Sistema de carrito con checkout integrado
- **Sistema de Pedidos**: Gestión de órdenes y seguimiento
- **Reviews y Calificaciones**: Sistema de reseñas de productos
- **Integración con IA**: Chat inteligente, análisis de productos y visión por computadora (OpenAI)
- **Autenticación**: Sistema completo con Laravel Breeze
- **Interfaz Moderna**: Diseño responsive con Tailwind CSS y Alpine.js

## 📋 Requisitos Previos

- **PHP**: >= 8.2
- **Composer**: >= 2.0
- **Node.js**: >= 18.0
- **NPM**: >= 9.0
- **Base de Datos**: MySQL, PostgreSQL o SQLite
- **OpenAI API Key**: Para funcionalidades de IA

## 🔧 Instalación y Configuración

### 1. Clonar el Repositorio

```bash
git clone https://github.com/Jasonelp/shopsmart-ia.git
cd shopsmart-ia
```

### 2. Instalar Dependencias

```bash
# Dependencias de PHP
composer install

# Dependencias de Node.js
npm install
```

### 3. Configurar Variables de Entorno

```bash
# Copiar el archivo de ejemplo (si no existe)
cp .env.example .env

# Generar la clave de aplicación
php artisan key:generate
```

Edita el archivo `.env` y configura:

```env
# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shopsmart_ia
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# OpenAI (para funcionalidades de IA)
OPENAI_API_KEY=tu_api_key_de_openai
```

### 4. Crear y Poblar la Base de Datos

#### Opción A: Usando Migraciones y Seeders (Recomendado)

```bash
# Ejecutar migraciones
php artisan migrate

# Poblar con datos de prueba
php artisan db:seed
```

#### Opción B: Usando el archivo SQL

Si prefieres usar el archivo SQL directamente:

```bash
# MySQL
mysql -u tu_usuario -p shopsmart_ia < database/schema-data.sql

# O desde MySQL CLI
mysql -u tu_usuario -p
> CREATE DATABASE shopsmart_ia;
> USE shopsmart_ia;
> SOURCE database/schema-data.sql;
```

### 5. Compilar Assets

```bash
# Desarrollo
npm run dev

# Producción
npm run build
```

### 6. Iniciar el Servidor

```bash
# Servidor de desarrollo
php artisan serve

# O usar el script de desarrollo completo (servidor + queue + logs + vite)
composer dev
```

La aplicación estará disponible en: `http://localhost:8000`

## 🎯 Inicio Rápido (Setup Automático)

Para una instalación rápida, usa el script de setup:

```bash
composer setup
```

Este comando ejecutará automáticamente:
- Instalación de dependencias de Composer
- Copia del archivo .env (si no existe)
- Generación de la clave de aplicación
- Ejecución de migraciones
- Instalación de dependencias de NPM
- Compilación de assets

## 📁 Estructura del Proyecto

```
shopsmart-ia/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php      # Panel de administración
│   │   │   ├── AIController.php         # Funcionalidades de IA
│   │   │   ├── CartController.php       # Carrito de compras
│   │   │   ├── CategoryController.php   # Gestión de categorías
│   │   │   ├── CheckoutController.php   # Proceso de pago
│   │   │   ├── ClientController.php     # Dashboard de cliente
│   │   │   ├── HomeController.php       # Página principal
│   │   │   ├── OrderController.php      # Gestión de pedidos
│   │   │   ├── ProductController.php    # Gestión de productos
│   │   │   ├── ProfileController.php    # Perfil de usuario
│   │   │   ├── ReviewController.php     # Sistema de reseñas
│   │   │   └── VendorController.php     # Dashboard de vendedor
│   │   └── Middleware/                  # Middlewares personalizados
│   ├── Models/
│   │   ├── Category.php                 # Modelo de categorías
│   │   ├── Order.php                    # Modelo de pedidos
│   │   ├── Product.php                  # Modelo de productos
│   │   ├── Review.php                   # Modelo de reseñas
│   │   └── User.php                     # Modelo de usuarios
│   └── View/                            # View composers
├── database/
│   ├── migrations/                      # Migraciones de base de datos
│   ├── seeders/                         # Seeders con datos de prueba
│   │   ├── CategorySeeder.php           # 6 categorías
│   │   ├── ProductSeeder.php            # 14 productos
│   │   └── DatabaseSeeder.php           # Seeder principal
│   └── schema-data.sql                  # Schema completo + datos
├── resources/
│   ├── views/                           # Vistas Blade
│   ├── css/                             # Estilos CSS
│   └── js/                              # JavaScript
├── routes/
│   ├── web.php                          # Rutas web
│   ├── auth.php                         # Rutas de autenticación
│   └── console.php                      # Comandos de consola
├── public/                              # Assets públicos
├── storage/                             # Almacenamiento
├── tests/                               # Tests automatizados
├── .env                                 # Variables de entorno
├── composer.json                        # Dependencias PHP
├── package.json                         # Dependencias Node.js
└── README.md                            # Este archivo
```

## 🗄️ Estructura de la Base de Datos

### Tablas Principales

- **users**: Usuarios del sistema (clientes, vendedores, administradores)
- **categories**: Categorías de productos
- **products**: Catálogo de productos
- **orders**: Pedidos realizados
- **order_product**: Tabla pivote (relación muchos a muchos)
- **reviews**: Reseñas y calificaciones de productos
- **sessions**: Sesiones de usuario
- **cache**: Sistema de caché
- **jobs**: Cola de trabajos

### Relaciones

- Un **usuario** puede tener muchos **pedidos**
- Un **pedido** puede tener muchos **productos** (relación muchos a muchos)
- Un **producto** pertenece a una **categoría**
- Un **producto** puede tener muchas **reseñas**
- Una **reseña** pertenece a un **usuario** y a un **producto**

## 👥 Usuarios de Prueba

Después de ejecutar los seeders, tendrás disponible:

- **Email**: test@example.com
- **Password**: password (deberás establecerla en el registro)

## 🛣️ Rutas Principales

### Rutas Públicas
- `/` - Página principal
- `/productos` - Catálogo de productos
- `/producto/{id}` - Detalle de producto
- `/categorias` - Lista de categorías
- `/categoria/{id}` - Productos por categoría

### Rutas de IA (Públicas)
- `POST /ai/chat` - Chat con IA
- `GET /ai/product/{id}` - Análisis de producto con IA
- `POST /ai/vision` - Análisis de imágenes

### Rutas Autenticadas
- `/dashboard` - Dashboard (redirige según rol)
- `/cart` - Carrito de compras
- `/checkout` - Proceso de pago
- `/my-orders` - Mis pedidos
- `/profile` - Perfil de usuario

### Rutas de Cliente
- `/cliente/dashboard` - Dashboard del cliente

### Rutas de Vendedor
- `/vendedor/dashboard` - Dashboard del vendedor
- `/vendedor/productos` - Gestión de productos
- `/vendedor/pedidos` - Pedidos del vendedor

### Rutas de Administrador
- `/admin/dashboard` - Dashboard del administrador
- `/admin/usuarios` - Gestión de usuarios
- `/admin/ventas` - Historial de ventas
- `/admin/products` - CRUD de productos
- `/admin/categories` - CRUD de categorías
- `/admin/orders` - CRUD de pedidos

## 🧪 Testing

```bash
# Ejecutar todos los tests
composer test

# O directamente con artisan
php artisan test
```

## 🛠️ Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Refrescar base de datos con seeders
php artisan migrate:fresh --seed

# Ver rutas disponibles
php artisan route:list

# Ejecutar cola de trabajos
php artisan queue:work

# Ver logs en tiempo real
php artisan pail
```

## 📦 Tecnologías Utilizadas

### Backend
- **Laravel 12**: Framework PHP
- **Laravel Breeze**: Autenticación
- **OpenAI PHP Client**: Integración con IA

### Frontend
- **Tailwind CSS**: Framework CSS
- **Alpine.js**: Framework JavaScript reactivo
- **Vite**: Build tool
- **Axios**: Cliente HTTP

### Base de Datos
- **MySQL/PostgreSQL/SQLite**: Bases de datos soportadas
- **Eloquent ORM**: ORM de Laravel

## 🤖 Funcionalidades de IA

El proyecto integra OpenAI para:
- **Chat Inteligente**: Asistente virtual para ayudar a los usuarios
- **Análisis de Productos**: Recomendaciones personalizadas
- **Visión por Computadora**: Análisis de imágenes de productos

## 📝 Licencia

Este proyecto está licenciado bajo la [Licencia MIT](https://opensource.org/licenses/MIT).

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor, abre un issue o pull request para sugerencias o mejoras.

## 📧 Contacto

Para más información o soporte, por favor contacta al equipo de desarrollo.

---

Desarrollado con ❤️ usando Laravel y tecnologías modernas de IA
