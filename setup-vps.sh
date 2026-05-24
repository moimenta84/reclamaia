#!/bin/bash
# ReclamaIA — Script de provisioning para IONOS VPS (Ubuntu 22.04)
# Ejecutar como root: bash setup-vps.sh
# Tiempo estimado: ~15 minutos

set -euo pipefail

# ─────────────────────────────────────────────
# CONFIGURA ESTAS VARIABLES ANTES DE EJECUTAR
# ─────────────────────────────────────────────
DOMAIN="reclamaia.es"                      # Tu dominio
DB_NAME="reclamaia"
DB_USER="reclamaia"
DB_PASS="$(openssl rand -base64 24)"       # Se genera automáticamente
DEPLOY_USER="deploy"
GITHUB_REPO="git@github.com:TU_USUARIO/reclamaia.git"   # Tu repo privado
# ─────────────────────────────────────────────

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
log()  { echo -e "${GREEN}[✓]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }
err()  { echo -e "${RED}[✗]${NC} $1"; exit 1; }

[[ $EUID -ne 0 ]] && err "Ejecuta como root: sudo bash setup-vps.sh"

log "Actualizando sistema..."
apt-get update -qq && apt-get upgrade -y -qq

# ── Usuario deploy ────────────────────────────────────────────
log "Creando usuario $DEPLOY_USER..."
if ! id "$DEPLOY_USER" &>/dev/null; then
    useradd -m -s /bin/bash "$DEPLOY_USER"
    mkdir -p /home/$DEPLOY_USER/.ssh
    cp /root/.ssh/authorized_keys /home/$DEPLOY_USER/.ssh/ 2>/dev/null || true
    chown -R $DEPLOY_USER:$DEPLOY_USER /home/$DEPLOY_USER/.ssh
    chmod 700 /home/$DEPLOY_USER/.ssh
    chmod 600 /home/$DEPLOY_USER/.ssh/authorized_keys 2>/dev/null || true
    echo "$DEPLOY_USER ALL=(ALL) NOPASSWD: /bin/systemctl restart reclamaia-python, /bin/systemctl restart php8.3-fpm, /bin/systemctl reload nginx" >> /etc/sudoers.d/deploy
fi

# ── PHP 8.3 ──────────────────────────────────────────────────
log "Instalando PHP 8.3..."
apt-get install -y -qq software-properties-common
add-apt-repository -y ppa:ondrej/php >/dev/null 2>&1
apt-get update -qq
apt-get install -y -qq \
    php8.3 php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml \
    php8.3-curl php8.3-zip php8.3-redis php8.3-gd php8.3-intl \
    php8.3-bcmath php8.3-tokenizer php8.3-fileinfo

# ── Composer ─────────────────────────────────────────────────
log "Instalando Composer..."
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer >/dev/null

# ── MySQL 8 ──────────────────────────────────────────────────
log "Instalando MySQL 8..."
apt-get install -y -qq mysql-server
systemctl enable --now mysql

mysql -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';"
mysql -e "GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
log "MySQL: base de datos '$DB_NAME' creada"

# ── Redis ─────────────────────────────────────────────────────
log "Instalando Redis..."
apt-get install -y -qq redis-server
systemctl enable --now redis-server

# ── Python 3.12 ──────────────────────────────────────────────
log "Instalando Python 3.12..."
add-apt-repository -y ppa:deadsnakes/ppa >/dev/null 2>&1
apt-get update -qq
apt-get install -y -qq python3.12 python3.12-venv python3.12-dev python3-pip

# ── Node.js 20 (para assets Laravel si los necesitas) ────────
log "Instalando Node.js 20..."
curl -fsSL https://deb.nodesource.com/setup_20.x | bash - >/dev/null 2>&1
apt-get install -y -qq nodejs

# ── Nginx ─────────────────────────────────────────────────────
log "Instalando Nginx..."
apt-get install -y -qq nginx
systemctl enable nginx

# ── Certbot (SSL) ─────────────────────────────────────────────
log "Instalando Certbot..."
apt-get install -y -qq certbot python3-certbot-nginx

# ── Git ───────────────────────────────────────────────────────
apt-get install -y -qq git

# ── Estructura del proyecto ───────────────────────────────────
log "Creando directorios del proyecto..."
mkdir -p /var/www/reclamaia
chown -R $DEPLOY_USER:www-data /var/www/reclamaia
chmod -R 775 /var/www/reclamaia

# ── Clonar repo ───────────────────────────────────────────────
log "Clonando repositorio..."
if [ ! -d "/var/www/reclamaia/laravel" ]; then
    sudo -u $DEPLOY_USER git clone "$GITHUB_REPO" /var/www/reclamaia/repo
    cp -r /var/www/reclamaia/repo/laravel /var/www/reclamaia/
    cp -r /var/www/reclamaia/repo/python-service /var/www/reclamaia/
    cp -r /var/www/reclamaia/repo/n8n /var/www/reclamaia/
    rm -rf /var/www/reclamaia/repo
fi

# ── Laravel setup ─────────────────────────────────────────────
log "Configurando Laravel..."
cd /var/www/reclamaia/laravel
sudo -u $DEPLOY_USER composer install --no-dev --optimize-autoloader -q

if [ ! -f ".env" ]; then
    cp .env.example .env
    # Rellenar DB automáticamente
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_NAME|" .env
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USER|" .env
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASS|" .env
    sed -i "s|APP_URL=.*|APP_URL=https://$DOMAIN|" .env
    sudo -u $DEPLOY_USER php artisan key:generate --force
    warn "IMPORTANTE: Edita /var/www/reclamaia/laravel/.env y añade STRIPE_KEY, STRIPE_SECRET, STRIPE_WEBHOOK_SECRET, INTERNAL_API_SECRET"
fi

# Permisos storage
chown -R $DEPLOY_USER:www-data /var/www/reclamaia/laravel/storage
chmod -R 775 /var/www/reclamaia/laravel/storage
chown -R $DEPLOY_USER:www-data /var/www/reclamaia/laravel/bootstrap/cache
chmod -R 775 /var/www/reclamaia/laravel/bootstrap/cache

# ── Python service setup ──────────────────────────────────────
log "Configurando Python service..."
cd /var/www/reclamaia/python-service
python3.12 -m venv venv
venv/bin/pip install -q -r requirements.txt

if [ ! -f ".env" ]; then
    cp .env.example .env
    warn "IMPORTANTE: Edita /var/www/reclamaia/python-service/.env y añade ANTHROPIC_API_KEY e INTERNAL_API_SECRET"
fi

# Google Cloud Document AI — instalar credenciales si se usa DocAI
if [ -f "/root/gcloud-key.json" ]; then
    log "Configurando Google Cloud credentials..."
    cp /root/gcloud-key.json /var/www/reclamaia/python-service/gcloud-key.json
    chown $DEPLOY_USER:$DEPLOY_USER /var/www/reclamaia/python-service/gcloud-key.json
    sed -i "s|#GOOGLE_APPLICATION_CREDENTIALS=.*|GOOGLE_APPLICATION_CREDENTIALS=/var/www/reclamaia/python-service/gcloud-key.json|" \
        /var/www/reclamaia/python-service/.env 2>/dev/null || true
    warn "Google Cloud credentials configuradas. Asegúrate de que GOOGLE_CLOUD_PROJECT y GOOGLE_DOCAI_PROCESSOR_ID están en .env"
else
    warn "Para Google Document AI: sube tu gcloud-key.json a /root/gcloud-key.json antes de ejecutar"
fi

chown -R $DEPLOY_USER:$DEPLOY_USER /var/www/reclamaia/python-service

# ── Systemd: Python service ───────────────────────────────────
log "Configurando servicio systemd para Python..."
cat > /etc/systemd/system/reclamaia-python.service << EOF
[Unit]
Description=ReclamaIA Python FastAPI
After=network.target

[Service]
User=$DEPLOY_USER
WorkingDirectory=/var/www/reclamaia/python-service
EnvironmentFile=/var/www/reclamaia/python-service/.env
ExecStart=/var/www/reclamaia/python-service/venv/bin/uvicorn app.main:app --host 127.0.0.1 --port 8001 --workers 2
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable reclamaia-python

# ── Systemd: Laravel queue worker (Horizon alternativo simple) ─
log "Configurando queue worker..."
cat > /etc/systemd/system/reclamaia-queue.service << EOF
[Unit]
Description=ReclamaIA Laravel Queue Worker
After=network.target mysql.service redis.service

[Service]
User=$DEPLOY_USER
WorkingDirectory=/var/www/reclamaia/laravel
ExecStart=/usr/bin/php8.3 artisan queue:work --sleep=3 --tries=3 --timeout=90 --queue=default
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable reclamaia-queue

# ── Systemd: Laravel scheduler ────────────────────────────────
log "Configurando scheduler (escalaciones automáticas)..."
cat > /etc/systemd/system/reclamaia-scheduler.service << EOF
[Unit]
Description=ReclamaIA Laravel Scheduler

[Service]
Type=oneshot
User=$DEPLOY_USER
WorkingDirectory=/var/www/reclamaia/laravel
ExecStart=/usr/bin/php8.3 artisan schedule:run
EOF

cat > /etc/systemd/system/reclamaia-scheduler.timer << EOF
[Unit]
Description=ReclamaIA Laravel Scheduler Timer

[Timer]
OnBootSec=1min
OnUnitActiveSec=1min

[Install]
WantedBy=timers.target
EOF

systemctl daemon-reload
systemctl enable reclamaia-scheduler.timer

# ── Nginx config ──────────────────────────────────────────────
log "Configurando Nginx..."
cat > /etc/nginx/sites-available/reclamaia << 'EOF'
server {
    listen 80;
    server_name DOMAIN_PLACEHOLDER www.DOMAIN_PLACEHOLDER;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name DOMAIN_PLACEHOLDER www.DOMAIN_PLACEHOLDER;

    root /var/www/reclamaia/laravel/public;
    index index.php;

    client_max_body_size 15M;

    ssl_certificate /etc/letsencrypt/live/DOMAIN_PLACEHOLDER/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/DOMAIN_PLACEHOLDER/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 60;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Block direct access to Python service
    location /internal/ {
        deny all;
    }
}
EOF

sed -i "s/DOMAIN_PLACEHOLDER/$DOMAIN/g" /etc/nginx/sites-available/reclamaia
ln -sf /etc/nginx/sites-available/reclamaia /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx

# ── PHP-FPM tuning (VPS 4GB) ─────────────────────────────────
log "Ajustando PHP-FPM para VPS..."
sed -i 's/pm.max_children = .*/pm.max_children = 10/' /etc/php/8.3/fpm/pool.d/www.conf
sed -i 's/pm.start_servers = .*/pm.start_servers = 3/' /etc/php/8.3/fpm/pool.d/www.conf
sed -i 's/pm.min_spare_servers = .*/pm.min_spare_servers = 2/' /etc/php/8.3/fpm/pool.d/www.conf
sed -i 's/pm.max_spare_servers = .*/pm.max_spare_servers = 5/' /etc/php/8.3/fpm/pool.d/www.conf
systemctl restart php8.3-fpm

# ── Firewall UFW ──────────────────────────────────────────────
log "Configurando firewall..."
ufw --force reset >/dev/null
ufw default deny incoming >/dev/null
ufw default allow outgoing >/dev/null
ufw allow 22/tcp    >/dev/null  # SSH
ufw allow 80/tcp    >/dev/null  # HTTP
ufw allow 443/tcp   >/dev/null  # HTTPS
ufw deny 8001/tcp   >/dev/null  # Python — solo interno
ufw deny 3306/tcp   >/dev/null  # MySQL — solo interno
ufw deny 6379/tcp   >/dev/null  # Redis — solo interno
ufw --force enable  >/dev/null
log "Firewall activo"

# ── SSL (Certbot) ─────────────────────────────────────────────
log "Obteniendo certificado SSL..."
warn "Para el SSL necesitas que el dominio $DOMAIN ya apunte a este servidor."
warn "Si el dominio NO apunta todavía, pulsa Ctrl+C y ejecuta certbot manualmente después:"
warn "  certbot --nginx -d $DOMAIN -d www.$DOMAIN"
read -t 10 -p "¿El dominio ya apunta a este servidor? [s/N]: " DOMAIN_READY || true
if [[ "$DOMAIN_READY" =~ ^[Ss]$ ]]; then
    certbot --nginx -d "$DOMAIN" -d "www.$DOMAIN" --non-interactive --agree-tos -m "admin@$DOMAIN" || warn "SSL falló — configúralo manualmente con: certbot --nginx -d $DOMAIN"
fi

# ── Migraciones Laravel ───────────────────────────────────────
log "Ejecutando migraciones..."
cd /var/www/reclamaia/laravel
sudo -u $DEPLOY_USER php artisan migrate --force 2>/dev/null || warn "Migraciones fallaron — revisa el .env y vuelve a ejecutar: php artisan migrate"
sudo -u $DEPLOY_USER php artisan storage:link || true
sudo -u $DEPLOY_USER php artisan optimize

# ── Iniciar servicios ─────────────────────────────────────────
log "Iniciando servicios..."
systemctl start reclamaia-python
systemctl start reclamaia-queue
systemctl start reclamaia-scheduler.timer

# ── Verificación ──────────────────────────────────────────────
echo ""
echo "══════════════════════════════════════════════════"
log "INSTALACIÓN COMPLETADA"
echo "══════════════════════════════════════════════════"
echo ""
echo "  App:        https://$DOMAIN"
echo "  Python:     $(curl -s http://localhost:8001/health 2>/dev/null || echo 'Arrancando...')"
echo ""
echo "  DB password guardada en: /root/.reclamaia-db-pass"
echo "$DB_PASS" > /root/.reclamaia-db-pass
chmod 600 /root/.reclamaia-db-pass
echo ""
warn "PASOS MANUALES QUE QUEDAN:"
echo "  1. nano /var/www/reclamaia/laravel/.env"
echo "     → Añadir: STRIPE_KEY, STRIPE_SECRET, STRIPE_WEBHOOK_SECRET"
echo "     → Añadir: INTERNAL_API_SECRET=<clave-secreta-larga>"
echo "     → Añadir: SIGNATURIT_API_KEY=<key de signaturit.com>"
echo "     → Añadir: SIGNATURIT_SANDBOX=false (producción)"
echo "     → Añadir: GOOGLE_CLOUD_PROJECT=<tu-proyecto-gcp>"
echo "     → Añadir: GOOGLE_DOCAI_PROCESSOR_ID=<processor-id>"
echo "     → Añadir: DAMAGE_VALUATION_PROVIDER=mock (o dat/audatex/gt)"
echo "     → Añadir: DAT_API_KEY, AUDATEX_USERNAME, AUDATEX_PASSWORD, GT_API_KEY (según proveedor)"
echo ""
echo "  2. nano /var/www/reclamaia/python-service/.env"
echo "     → Añadir: ANTHROPIC_API_KEY=sk-ant-..."
echo "     → Añadir: INTERNAL_API_SECRET=<misma-clave-que-arriba>"
echo "     → Añadir: STORAGE_PATH=/var/www/reclamaia/laravel/storage/app"
echo "     → Añadir: DAMAGE_VALUATION_PROVIDER=mock (misma que Laravel)"
echo "     → (Opcional) GOOGLE_APPLICATION_CREDENTIALS=/var/www/reclamaia/python-service/gcloud-key.json"
echo ""
echo "  3. Stripe webhook:"
echo "     → Stripe Dashboard > Webhooks > Add endpoint"
echo "     → URL: https://$DOMAIN/api/stripe/webhook"
echo "     → Eventos: payment_intent.succeeded, payment_intent.payment_failed,"
echo "                customer.subscription.*, invoice.payment_failed"
echo ""
echo "  4. Reiniciar servicios después de editar .env:"
echo "     systemctl restart reclamaia-python reclamaia-queue"
echo "     cd /var/www/reclamaia/laravel && php artisan optimize"
echo ""
echo "  5. Verificación final:"
echo "     curl http://localhost:8001/health"
echo "     curl https://$DOMAIN/up"
echo "══════════════════════════════════════════════════"
