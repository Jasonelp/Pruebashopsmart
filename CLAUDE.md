Quiero implementar el chatbot de IA en Pruebashopsmart usando OpenRouter.

Sigue las reglas definidas en CLAUDE.md.

Tareas:

1. Crear app/Services/AiService.php con integración OpenRouter usando AI_MODEL y OPENROUTER_API_KEY.
2. Crear app/Http/Controllers/ChatController.php.
3. Crear la ruta POST /api/chat en routes/api.php sin modificar otras rutas.
4. Crear modelos Conversation y Message.
5. Crear las migraciones correspondientes sin tocar migraciones antiguas.
6. Implementar memoria de conversación por usuario.
7. Hacer que la IA recomiende productos reales desde la tabla products.
8. Crear pruebas Feature para /api/chat.
9. Actualizar API_DOCUMENTATION.md sin borrar secciones existentes.
10. Crear todo en una nueva rama: feature/chatbot-ia
11. Al finalizar, abrir un Pull Request limpio.

No modifiques frontend.
No modifiques código existente fuera de los archivos nuevos.
No reformatees archivos que no se soliciten.
