# n8n — Pipeline de Desarrollo Autónomo

## Configurar credenciales en n8n

### SSH Credential
- Name: `reclamaia-vps`
- Host: IP del VPS Hetzner
- Port: 22
- Username: `deploy` (o el usuario SSH configurado)
- Private Key: contenido de `~/.ssh/id_rsa` del propietario

### Telegram Credential
- Name: `reclamaia-telegram`
- Bot Token: obtenido de @BotFather
- Chat ID: obtenido de @userinfobot

### Variables de entorno del workflow
Configurar en n8n > Settings > Environment Variables:
```
VPS_PROJECT_PATH=/var/www/reclamaia
ANTHROPIC_API_KEY=sk-ant-...
TASKS_FILE_PATH=/var/www/reclamaia/specs/001-reclamaia-generador/tasks.md
TELEGRAM_CHAT_ID=123456789
```

## Importar el workflow
1. Abrir n8n en http://VPS_IP:5678
2. Workflows > Import from File
3. Seleccionar `workflows/dev-pipeline.json`
4. Configurar las credenciales SSH y Telegram
5. Activar el workflow

## Estructura del workflow
1. **Cron** → 02:00 CET diario
2. **SSH: leer tasks.md** → obtiene contenido del archivo
3. **Code: parsear tarea** → extrae primera tarea `- [ ]`
4. **HTTP Request: Claude API** → genera código para la tarea
5. **Code: extraer archivos** → parsea archivos del response
6. **SSH: escribir archivos** → escribe en el VPS
7. **SSH: ejecutar tests** → `php artisan test && pytest`
8. **IF: tests ok**
   - ✅ → SSH: `git add . && git commit && git push && php artisan optimize`
   - ❌ → Telegram: notificación de fallo
9. **Telegram** → notificación de resultado (éxito o fallo)

## Formato esperado del response de Claude
El workflow busca bloques de código con rutas de archivo:
```
FILE: path/to/file.php
```php
<?php
// contenido del archivo
```
```

El nodo Code extrae cada bloque FILE y lo escribe en la ruta correspondiente.
