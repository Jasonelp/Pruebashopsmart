-- ============================================
-- ShopSmart IA - Database Schema & Test Data
-- ============================================
-- Este archivo contiene el esquema completo de la base de datos
-- y datos de prueba para desarrollo y testing
-- ============================================

-- Crear la base de datos (descomentar si es necesario)
-- CREATE DATABASE IF NOT EXISTS shopsmart_ia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE shopsmart_ia;

-- ============================================
-- ELIMINAR TABLAS EXISTENTES (en orden correcto)
-- ============================================
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `order_product`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;

-- ============================================
-- TABLA: users
-- ============================================
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'cliente',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: password_reset_tokens
-- ============================================
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: sessions
-- ============================================
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: cache
-- ============================================
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: cache_locks
-- ============================================
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: jobs
-- ============================================
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: job_batches
-- ============================================
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: failed_jobs
-- ============================================
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: categories
-- ============================================
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: products
-- ============================================
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `category_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `specifications` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_user_id_foreign` (`user_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: orders
-- ============================================
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pendiente',
  `shipping_address` varchar(255) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: order_product (tabla pivote)
-- ============================================
CREATE TABLE `order_product` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_product_order_id_foreign` (`order_id`),
  KEY `order_product_product_id_foreign` (`product_id`),
  CONSTRAINT `order_product_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: reviews
-- ============================================
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `rating` int unsigned NOT NULL DEFAULT '5',
  `comment` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_product_id_foreign` (`product_id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DATOS DE PRUEBA
-- ============================================

-- ============================================
-- INSERTAR USUARIOS DE PRUEBA
-- ============================================
-- Contraseña para todos: "password" (hasheada con bcrypt)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@shopsmart.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANh2u8LhKSm', 'admin', NOW(), NOW()),
(2, 'Vendedor Demo', 'vendedor@shopsmart.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANh2u8LhKSm', 'vendedor', NOW(), NOW()),
(3, 'Cliente Test', 'cliente@shopsmart.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANh2u8LhKSm', 'cliente', NOW(), NOW()),
(4, 'Juan Pérez', 'juan@example.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANh2u8LhKSm', 'cliente', NOW(), NOW()),
(5, 'María García', 'maria@example.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANh2u8LhKSm', 'vendedor', NOW(), NOW());

-- ============================================
-- INSERTAR CATEGORÍAS
-- ============================================
INSERT INTO `categories` (`id`, `name`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Smartphones', 'Los mejores smartphones del mercado', NULL, NOW(), NOW()),
(2, 'Computadoras', 'Laptops y PCs de última generación', NULL, NOW(), NOW()),
(3, 'Cámaras', 'Cámaras profesionales y accesorios', NULL, NOW(), NOW()),
(4, 'Relojes', 'Relojes inteligentes y clásicos', NULL, NOW(), NOW()),
(5, 'Auriculares', 'Audio de alta calidad', NULL, NOW(), NOW()),
(6, 'Tablets', 'Tablets para trabajo y entretenimiento', NULL, NOW(), NOW());

-- ============================================
-- INSERTAR PRODUCTOS
-- ============================================
INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `category_id`, `user_id`, `image`, `specifications`, `created_at`, `updated_at`) VALUES
-- Smartphones
(1, 'iPhone 15 Pro - 256GB Titanio Natural', 'El smartphone más avanzado de Apple con chip A17 Pro y cámara de 48MP', 4899.00, 25, 1, 2, NULL, NULL, NOW(), NOW()),
(2, 'Samsung Galaxy S24 Ultra - 512GB', 'Potencia extrema con S Pen integrado y pantalla Dynamic AMOLED 2X', 4299.00, 18, 1, 2, NULL, NULL, NOW(), NOW()),
(3, 'Xiaomi Redmi Note 13 Pro - 256GB', 'Excelente relación calidad-precio con cámara de 200MP', 899.00, 45, 1, 5, NULL, NULL, NOW(), NOW()),
(4, 'Google Pixel 8 Pro - 128GB', 'Fotografía computacional de Google con IA avanzada', 3499.00, 30, 1, 2, NULL, NULL, NOW(), NOW()),

-- Computadoras
(5, 'MacBook Pro 16" M3 Pro', 'Rendimiento profesional para creativos y desarrolladores', 8999.00, 12, 2, 2, NULL, NULL, NOW(), NOW()),
(6, 'Dell XPS 15 - Intel i9', 'Laptop premium con pantalla OLED 4K', 6499.00, 15, 2, 5, NULL, NULL, NOW(), NOW()),
(7, 'Lenovo ThinkPad X1 Carbon', 'Ultraligera y perfecta para negocios', 5299.00, 20, 2, 5, NULL, NULL, NOW(), NOW()),
(8, 'ASUS ROG Zephyrus G14', 'Gaming portátil con AMD Ryzen 9 y RTX 4060', 4999.00, 10, 2, 2, NULL, NULL, NOW(), NOW()),

-- Cámaras
(9, 'Canon EOS R6 Mark II', 'Cámara mirrorless full-frame de 24MP', 9499.00, 8, 3, 2, NULL, NULL, NOW(), NOW()),
(10, 'Sony Alpha A7 IV', 'Versátil cámara híbrida para foto y video', 8999.00, 10, 3, 5, NULL, NULL, NOW(), NOW()),
(11, 'Nikon Z8', 'Cámara profesional con sensor de 45.7MP', 12999.00, 5, 3, 2, NULL, NULL, NOW(), NOW()),

-- Relojes
(12, 'Apple Watch Series 9 GPS', 'El reloj inteligente más popular del mundo', 1599.00, 35, 4, 2, NULL, NULL, NOW(), NOW()),
(13, 'Samsung Galaxy Watch 6 Classic', 'Elegancia y tecnología en tu muñeca', 1299.00, 28, 4, 5, NULL, NULL, NOW(), NOW()),
(14, 'Garmin Fenix 7X', 'Reloj deportivo premium con GPS multibanda', 2999.00, 15, 4, 2, NULL, NULL, NOW(), NOW()),

-- Auriculares
(15, 'AirPods Pro 2da Generación', 'Cancelación de ruido adaptativa y audio espacial', 899.00, 50, 5, 2, NULL, NULL, NOW(), NOW()),
(16, 'Sony WH-1000XM5', 'Los mejores auriculares con cancelación de ruido', 1299.00, 30, 5, 5, NULL, NULL, NOW(), NOW()),
(17, 'Bose QuietComfort Ultra', 'Audio inmersivo con cancelación de ruido premium', 1499.00, 25, 5, 2, NULL, NULL, NOW(), NOW()),

-- Tablets
(18, 'iPad Pro 12.9" M2', 'La tablet más potente con chip M2', 4499.00, 15, 6, 2, NULL, NULL, NOW(), NOW()),
(19, 'Samsung Galaxy Tab S9 Ultra', 'Pantalla AMOLED gigante de 14.6 pulgadas', 3999.00, 12, 6, 5, NULL, NULL, NOW(), NOW()),
(20, 'Microsoft Surface Pro 9', 'Tablet 2 en 1 con Windows 11', 3299.00, 18, 6, 2, NULL, NULL, NOW(), NOW());

-- ============================================
-- INSERTAR PEDIDOS DE PRUEBA
-- ============================================
INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `shipping_address`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, 5798.00, 'completado', 'Av. Principal 123, Ciudad de México', 'Entrega en horario de oficina', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(2, 4, 2198.00, 'en_proceso', 'Calle Secundaria 456, Guadalajara', NULL, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(3, 3, 8999.00, 'pendiente', 'Av. Principal 123, Ciudad de México', 'Llamar antes de entregar', NOW(), NOW());

-- ============================================
-- INSERTAR PRODUCTOS EN PEDIDOS
-- ============================================
INSERT INTO `order_product` (`order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
-- Pedido 1
(1, 1, 1, 4899.00, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 15, 1, 899.00, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),

-- Pedido 2
(2, 16, 1, 1299.00, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 3, 1, 899.00, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),

-- Pedido 3
(3, 5, 1, 8999.00, NOW(), NOW());

-- ============================================
-- INSERTAR RESEÑAS DE PRUEBA
-- ============================================
INSERT INTO `reviews` (`product_id`, `user_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 3, 5, 'Excelente teléfono, la cámara es increíble y la batería dura todo el día.', DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
(1, 4, 4, 'Muy buen producto, aunque el precio es elevado. Vale la pena.', DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(3, 3, 5, 'Increíble relación calidad-precio. La cámara de 200MP es espectacular.', DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY)),
(15, 4, 5, 'Los mejores auriculares que he tenido. La cancelación de ruido es perfecta.', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),
(16, 3, 5, 'Calidad de audio excepcional. Muy cómodos para uso prolongado.', NOW(), NOW()),
(5, 4, 5, 'La MacBook Pro M3 es una bestia. Perfecta para desarrollo y diseño.', DATE_SUB(NOW(), INTERVAL 6 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY)),
(12, 3, 4, 'Buen smartwatch, aunque esperaba más funciones de salud.', DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY));

-- ============================================
-- FIN DEL SCRIPT
-- ============================================
-- Notas:
-- 1. Todos los usuarios tienen la contraseña: "password"
-- 2. Se incluyen 5 usuarios de prueba con diferentes roles
-- 3. Se incluyen 6 categorías de productos
-- 4. Se incluyen 20 productos distribuidos en las categorías
-- 5. Se incluyen 3 pedidos de ejemplo con sus productos
-- 6. Se incluyen 7 reseñas de productos
-- ============================================
