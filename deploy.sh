#!/bin/bash
# ReclamaIA — Script de despliegue continuo
# Ejecutar como deploy: bash deploy.sh [--skip-migrate]
# Uso desde CI/CD: ssh deploy@reclamaia.es 'bash /var/www/reclamaia/deploy.sh'

set -euo pipefail

LARAVEL_DIR="/var/www/reclamaia/laravel"
PYTHON_DIR="/var/www/reclamaia/python-service"
SKIP_MIGRATE="${1:-}"

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
log()  { echo -e "${GREEN}[✓]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }
err()  { echo -e "${RED}[✗]${NC} $1"; exit 1; }

log "=== Deploy ReclamaIA $(date '+%Y-%m-%d %H:%M:%S') ==="

# ── Bajar mantenimiento ───────────────────────────────────────
cd "$LARAVEL_DIR"
php artisan down --retry=60 2>/dev/null || true

# ── Git pull ─────────────────────────────────────────────────
log "Git pull..."
git pull --ff-only origin main

# ── Laravel: dependencias + optimización ─────────────────────
log "Composer install..."
composer install --no-dev --optimize-autoloader --no-interaction -q

log "Optimizando Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# ── Migraciones (opcional) ────────────────────────────────────
if [ "$SKIP_MIGRATE" != "--skip-migrate" ]; then
    log "Ejecutando migraciones..."
    php artisan migrate --force
fi

# ── Python service: dependencias ─────────────────────────────
log "Python pip install..."
cd "$PYTHON_DIR"
venv/bin/pip install -q -r requirements.txt

# ── Reiniciar servicios ───────────────────────────────────────
log "Reiniciando servicios..."
sudo systemctl restart reclamaia-python
sudo systemctl restart reclamaia-queue

# ── Levantar mantenimiento ────────────────────────────────────
cd "$LARAVEL_DIR"
php artisan up

log "=== Deploy completado ==="
curl -s http://localhost:8001/health && echo " ← Python OK" || warn "Python health check falló"
curl -s http://localhost/up > /dev/null && echo "Laravel /up OK" || warn "Laravel /up falló"
