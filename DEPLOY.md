# ReclamaIA — Guía de despliegue en IONOS VPS

## Requisitos previos

- VPS IONOS Ubuntu 22.04 (recomendado: 4 GB RAM, 2 vCPUs)
- Dominio `reclamaia.es` apuntando al IP del VPS
- Repo privado en GitHub
- Claves API: Stripe, Anthropic, Signaturit (opcional), Google Cloud (opcional)

---

## 1. Primer despliegue (una sola vez)

```bash
# Desde tu máquina local
ssh root@<IP_DEL_VPS>

# Clonar y ejecutar setup
curl -fsSL https://raw.githubusercontent.com/TU_USUARIO/reclamaia/main/setup-vps.sh -o setup-vps.sh
# Edita DOMAIN, GITHUB_REPO en las primeras líneas
nano setup-vps.sh
bash setup-vps.sh
```

El script instala PHP 8.3, MySQL, Redis, Python 3.12, Nginx, Certbot y configura todos los servicios systemd.

---

## 2. Variables de entorno a configurar tras el setup

### Laravel (`/var/www/reclamaia/laravel/.env`)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://reclamaia.es

# Base de datos (ya configurada por setup-vps.sh)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reclamaia
DB_USERNAME=reclamaia
DB_PASSWORD=<generada-por-setup>

# Queue y cache
QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis

# Stripe
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Python microservice (interno)
INTERNAL_API_SECRET=<clave-aleatoria-larga>
PYTHON_SERVICE_URL=http://127.0.0.1:8001

# Signaturit (firma digital eIDAS)
SIGNATURIT_API_KEY=<clave-de-signaturit.com>
SIGNATURIT_SANDBOX=false

# Google Document AI (OCR avanzado)
GOOGLE_CLOUD_PROJECT=<tu-proyecto-gcp>
GOOGLE_DOCAI_LOCATION=eu
GOOGLE_DOCAI_PROCESSOR_ID=<processor-id>

# Valoración de daños
DAMAGE_VALUATION_PROVIDER=mock   # o: dat | audatex | gt
DAT_API_KEY=
AUDATEX_USERNAME=
AUDATEX_PASSWORD=
GT_API_KEY=
```

### Python (`/var/www/reclamaia/python-service/.env`)

```env
ANTHROPIC_API_KEY=sk-ant-...
INTERNAL_API_SECRET=<misma-clave-que-laravel>
STORAGE_PATH=/var/www/reclamaia/laravel/storage/app
DAMAGE_VALUATION_PROVIDER=mock
# Google DocAI (opcional)
GOOGLE_APPLICATION_CREDENTIALS=/var/www/reclamaia/python-service/gcloud-key.json
GOOGLE_CLOUD_PROJECT=<tu-proyecto-gcp>
GOOGLE_DOCAI_PROCESSOR_ID=<processor-id>
```

Tras editar los `.env`:

```bash
cd /var/www/reclamaia/laravel && php artisan optimize
sudo systemctl restart reclamaia-python reclamaia-queue
```

---

## 3. SSL con Certbot

```bash
certbot --nginx -d reclamaia.es -d www.reclamaia.es
# Renovación automática (ya configurada por certbot)
systemctl status certbot.timer
```

---

## 4. Configurar webhooks

### Stripe

1. Dashboard Stripe > Desarrolladores > Webhooks > Añadir endpoint
2. URL: `https://reclamaia.es/api/stripe/webhook`
3. Eventos a escuchar:
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
   - `customer.subscription.created`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
   - `invoice.payment_failed`
4. Copia el `whsec_...` al `STRIPE_WEBHOOK_SECRET` del `.env`

### Signaturit

1. Panel Signaturit > Webhooks
2. URL: `https://reclamaia.es/webhook/signaturit`
3. Evento: `audit_trail_completed` (documento firmado)

---

## 5. Despliegues posteriores

```bash
ssh deploy@reclamaia.es 'bash /var/www/reclamaia/deploy.sh'
# o sin migraciones:
ssh deploy@reclamaia.es 'bash /var/www/reclamaia/deploy.sh --skip-migrate'
```

El script `deploy.sh`:
1. Activa modo mantenimiento
2. `git pull`
3. `composer install --no-dev`
4. `php artisan config:cache route:cache view:cache event:cache`
5. `php artisan migrate --force`
6. `pip install -r requirements.txt`
7. Reinicia `reclamaia-python` y `reclamaia-queue`
8. Desactiva modo mantenimiento

---

## 6. Verificación post-despliegue

```bash
# Health checks
curl https://reclamaia.es/up                        # 200
curl https://reclamaia.es/legal/privacidad           # 200
curl https://reclamaia.es/baremo                     # 200
curl http://localhost:8001/health                    # {"status":"ok"}

# Servicios systemd
systemctl is-active reclamaia-python reclamaia-queue reclamaia-scheduler.timer

# Logs
journalctl -u reclamaia-python -f
journalctl -u reclamaia-queue -f
tail -f /var/www/reclamaia/laravel/storage/logs/laravel.log
```

---

## 7. Seguridad — puertos bloqueados por UFW

| Puerto | Servicio | Política |
|--------|----------|----------|
| 22     | SSH      | Abierto  |
| 80     | HTTP     | Abierto (redirige a 443) |
| 443    | HTTPS    | Abierto  |
| 8001   | Python FastAPI | **Bloqueado** (solo localhost) |
| 3306   | MySQL    | **Bloqueado** (solo localhost) |
| 6379   | Redis    | **Bloqueado** (solo localhost) |

**IMPORTANTE**: Nginx nunca hace proxy a `127.0.0.1:8001`. El Python service es exclusivamente interno, llamado por Laravel con `INTERNAL_API_SECRET`.

---

## 8. Google Document AI (opcional)

Si se activa DocAI avanzado:

```bash
# En el VPS
pip install google-cloud-documentai>=2.29.0
# Ya incluido en requirements.txt

# Configurar credenciales
cp /ruta/local/gcloud-key.json /var/www/reclamaia/python-service/gcloud-key.json
chown deploy:deploy /var/www/reclamaia/python-service/gcloud-key.json
chmod 600 /var/www/reclamaia/python-service/gcloud-key.json
```

Sin DocAI, el servicio OCR usa `pypdf` como fallback — funcional para PDFs nativos digitales.

---

## 9. Smoke test completo

```bash
# Pago de prueba Stripe (usa tarjeta test 4242424242424242)
# 1. Abre https://reclamaia.es/reclamar
# 2. Rellena el formulario
# 3. En el pago usa la tarjeta test
# 4. Verifica que llega email de confirmación
# 5. Verifica que el webhook Stripe procesa en logs

# Baremo (público)
curl -s -X POST https://reclamaia.es/baremo/calcular \
  -H "Content-Type: application/json" \
  -d '{"edad":40,"dias_grave":30,"puntos_secuelas":5}' | jq .
```
