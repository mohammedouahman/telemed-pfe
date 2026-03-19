# ============================================================
# TELEMED — EXACT COMMANDS TO RUN (copy-paste in terminal)
# Starting point: your project already exists at ~/telemed-pfe
# ============================================================

# ══════════════════════════════════════════════════════
# PHASE A — CREATE THE DOCKER FILES (do this right now)
# ══════════════════════════════════════════════════════

cd ~/telemed-pfe

# 1. Create the nginx config directory
mkdir -p docker/nginx

# 2. Create nginx config
cat > docker/nginx/default.conf << 'EOF'
server {
    listen 80;
    index index.php index.html;
    root /var/www/public;
    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass backend:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# 3. Create docker-compose.yml at project root
cat > docker-compose.yml << 'EOF'
version: '3.8'

services:

  db:
    image: mysql:8.0
    container_name: telemed_db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: telemed_db
      MYSQL_USER: telemed
      MYSQL_PASSWORD: telemed123
      MYSQL_ROOT_PASSWORD: root_secret
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - telemed_network
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      timeout: 20s
      retries: 10

  backend:
    build:
      context: ./telemed-backend
      dockerfile: Dockerfile
    container_name: telemed_backend
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./telemed-backend:/var/www
      - /var/www/vendor
    depends_on:
      db:
        condition: service_healthy
    networks:
      - telemed_network

  nginx:
    image: nginx:alpine
    container_name: telemed_nginx
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - ./telemed-backend:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - backend
    networks:
      - telemed_network

  frontend:
    image: node:20-alpine
    container_name: telemed_frontend
    restart: unless-stopped
    working_dir: /app
    volumes:
      - ./telemed-frontend:/app
      - /app/node_modules
    ports:
      - "5173:5173"
    command: sh -c "npm install && npm run dev -- --host 0.0.0.0"
    depends_on:
      - nginx
    networks:
      - telemed_network

volumes:
  db_data:

networks:
  telemed_network:
    driver: bridge
EOF

# 4. Create Laravel Dockerfile
cat > telemed-backend/Dockerfile << 'EOF'
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev \
    libxml2-dev zip unzip libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
EOF

# 5. Create .env.docker for the backend
cat > telemed-backend/.env.docker << 'EOF'
APP_NAME=TeleMed
APP_ENV=local
APP_KEY=base64:2452ucza6/iv2TF9/p1Qvdqbau4qhAd9/4JfYBU0cCM=
APP_DEBUG=true
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=telemed_db
DB_USERNAME=telemed
DB_PASSWORD=telemed123

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=local
LOG_CHANNEL=stack
LOG_LEVEL=debug
BCRYPT_ROUNDS=12
EOF

# 6. Create .env for frontend
cat > telemed-frontend/.env << 'EOF'
VITE_API_URL=http://localhost:8000/api
EOF

# 7. Create Makefile (shortcuts)
cat > Makefile << 'EOF'
.PHONY: start stop reset logs shell-back shell-db

start:
	cp telemed-backend/.env.docker telemed-backend/.env
	docker-compose up -d --build
	@echo "Waiting for database to be ready (15s)..."
	@sleep 15
	docker exec telemed_backend php artisan migrate --seed --force
	docker exec telemed_backend php artisan storage:link
	@echo ""
	@echo "==================================="
	@echo "  TeleMed is RUNNING!"
	@echo "==================================="
	@echo "  Frontend: http://localhost:5173"
	@echo "  Backend:  http://localhost:8000"
	@echo "-----------------------------------"
	@echo "  admin@telemed.ma   / admin123"
	@echo "  arrami@telemed.ma  / doctor123"
	@echo "  jean@telemed.ma    / patient123"
	@echo "==================================="

stop:
	docker-compose down

reset:
	docker-compose down -v
	cp telemed-backend/.env.docker telemed-backend/.env
	docker-compose up -d --build
	@sleep 20
	docker exec telemed_backend php artisan migrate:fresh --seed --force
	docker exec telemed_backend php artisan storage:link

logs:
	docker-compose logs -f

shell-back:
	docker exec -it telemed_backend bash

shell-db:
	docker exec -it telemed_db mysql -u telemed -ptelemed123 telemed_db
EOF

# 8. Update .gitignore
cat > .gitignore << 'EOF'
# Laravel
telemed-backend/.env
telemed-backend/vendor/
telemed-backend/storage/logs/*.log

# React
telemed-frontend/node_modules/
telemed-frontend/dist/

# Docker volumes
docker/mysql/

# OS
.DS_Store
Thumbs.db
EOF

echo "✅ All Docker files created!"
echo "Now run: make start"


# ══════════════════════════════════════════════════════
# PHASE B — FIRST RUN (test it works on YOUR machine)
# ══════════════════════════════════════════════════════

# Make sure Docker Desktop is running first, then:
make start

# Wait ~2 minutes for everything to boot.
# Then open:
#   http://localhost:5173   ← React frontend
#   http://localhost:8000/api/specialties  ← test API works

# If something goes wrong:
docker-compose logs backend    # check Laravel errors
docker-compose logs db         # check MySQL errors
docker-compose logs frontend   # check React errors

# Nuclear option (full reset):
make reset


# ══════════════════════════════════════════════════════
# PHASE C — PUSH TO GITHUB (share with Zineb/Mohammed)
# ══════════════════════════════════════════════════════

cd ~/telemed-pfe

# Initialize git if not done yet
git init
git add .
git commit -m "feat: add Docker setup for local development"

# Create repo on github.com then:
git remote add origin git@github.com:mohammedouahman/telemed-pfe.git
git branch -M main
git push -u origin main

# ── Your colleague clones it: ──
# git clone https://github.com/YOUR_USERNAME/telemed-pfe.git
# cd telemed-pfe
# make start
# Done. No PHP, no MySQL, no config needed.


# ══════════════════════════════════════════════════════
# PHASE D — DEPLOY ONLINE (Railway + Vercel)
# ══════════════════════════════════════════════════════

# ── D1: BACKEND on Railway ──────────────────────────

# 1. Go to https://railway.app → Login with GitHub

# 2. New Project → Deploy from GitHub repo
#    Select: telemed-pfe (or telemed-backend subfolder)
#    If monorepo: set Root Directory = telemed-backend

# 3. Add MySQL: + New → Database → MySQL
#    Railway auto-injects these variables:
#    MYSQLHOST, MYSQLPORT, MYSQLDATABASE, MYSQLUSER, MYSQLPASSWORD

# 4. Create this file at telemed-backend root:
cat > telemed-backend/railway.json << 'EOF'
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php artisan serve --host=0.0.0.0 --port=$PORT",
    "releaseCommand": "php artisan migrate --seed --force"
  }
}
EOF

# 5. Create telemed-backend/nixpacks.toml (tells Railway how to build PHP)
cat > telemed-backend/nixpacks.toml << 'EOF'
[phases.setup]
nixPkgs = ["php82", "php82Extensions.pdo_mysql", "php82Extensions.mbstring", "php82Extensions.zip", "php82Extensions.gd", "composer"]

[phases.install]
cmds = ["composer install --no-dev --optimize-autoloader"]

[start]
cmd = "php artisan serve --host=0.0.0.0 --port=$PORT"
EOF

# 6. In Railway dashboard → Variables tab, add:
#    APP_NAME=TeleMed
#    APP_ENV=production
#    APP_DEBUG=false
#    APP_KEY=base64:2452ucza6/iv2TF9/p1Qvdqbau4qhAd9/4JfYBU0cCM=
#    DB_CONNECTION=mysql
#    DB_HOST=${{MySQL.MYSQLHOST}}         ← use Railway reference syntax
#    DB_PORT=${{MySQL.MYSQLPORT}}
#    DB_DATABASE=${{MySQL.MYSQLDATABASE}}
#    DB_USERNAME=${{MySQL.MYSQLUSER}}
#    DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
#    SESSION_DRIVER=database
#    CACHE_STORE=database
#    QUEUE_CONNECTION=database
#    FRONTEND_URL=https://telemed-pfe.vercel.app   ← fill after Vercel deploy
#    SANCTUM_STATEFUL_DOMAINS=telemed-pfe.vercel.app

# 7. Also update config/cors.php in your Laravel project:
#    'allowed_origins' => [
#        'http://localhost:5173',
#        'https://telemed-pfe.vercel.app',   ← your Vercel URL
#    ],

# 8. Deploy → Railway gives you URL like:
#    https://telemed-backend-production.up.railway.app
#    SAVE THIS URL.

# ── D2: FRONTEND on Vercel ───────────────────────────

# 1. Create telemed-frontend/.env.production
cat > telemed-frontend/.env.production << 'EOF'
VITE_API_URL=https://telemed-backend-production.up.railway.app/api
EOF
# Replace with your actual Railway URL above

# 2. Make sure api.js uses the env variable:
#    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

# 3. Create telemed-frontend/vercel.json
cat > telemed-frontend/vercel.json << 'EOF'
{
  "rewrites": [
    { "source": "/(.*)", "destination": "/index.html" }
  ]
}
EOF

# 4. Go to https://vercel.com → New Project → Import GitHub repo
#    Root Directory: telemed-frontend
#    Framework: Vite
#    Build command: npm run build
#    Output dir: dist
#    Environment variable: VITE_API_URL = https://YOUR-RAILWAY-URL/api

# 5. Deploy → Vercel gives you:
#    https://telemed-pfe.vercel.app

# 6. Go back to Railway → update FRONTEND_URL and SANCTUM_STATEFUL_DOMAINS
#    with your real Vercel URL → redeploy backend

# 7. FINAL TEST — open https://telemed-pfe.vercel.app
#    Login as jean@telemed.ma / patient123 → should work!


# ══════════════════════════════════════════════════════
# PHASE E — VERIFY EVERYTHING (run this checklist)
# ══════════════════════════════════════════════════════

# Open these URLs and confirm each works:

# Local (Docker):
# [ ] http://localhost:5173                    → Landing page
# [ ] http://localhost:5173/login              → Login page
# [ ] Login as jean@telemed.ma / patient123    → Patient dashboard
# [ ] Login as arrami@telemed.ma / doctor123   → Doctor dashboard
# [ ] Login as admin@telemed.ma / admin123     → Admin dashboard
# [ ] Book an appointment as patient           → Success
# [ ] Confirm appointment as doctor            → Status changes
# [ ] Join video call                          → Jitsi opens
# [ ] Download prescription PDF               → File downloads
# [ ] Admin validates Dr. Karim               → Works

# Online (Vercel + Railway):
# [ ] https://telemed-pfe.vercel.app           → Landing page
# [ ] All above tests repeated on live URL


# ══════════════════════════════════════════════════════
# TROUBLESHOOTING (common issues)
# ══════════════════════════════════════════════════════

# Problem: "DB connection refused" on first start
# Fix: Database takes time to start. Run:
docker exec telemed_backend php artisan migrate --seed --force
# (after waiting 20-30 seconds)

# Problem: "Permission denied" on storage
# Fix:
docker exec telemed_backend chmod -R 775 /var/www/storage
docker exec telemed_backend chown -R www-data:www-data /var/www/storage

# Problem: Frontend says "Network Error" / can't reach API
# Fix: Check VITE_API_URL in .env, make sure backend is running on :8000
# Test: curl http://localhost:8000/api/specialties

# Problem: CORS error in browser console
# Fix: Check config/cors.php allows http://localhost:5173

# Problem: Railway deploy fails
# Fix: Check build logs in Railway dashboard
# Most common: missing APP_KEY or wrong DB variables

# Problem: Vercel shows blank page
# Fix: Make sure vercel.json exists with the rewrite rule
# Check browser console for errors

# Problem: colleague runs "make start" and it fails
# Most likely cause: Docker Desktop not running
# Fix: Start Docker Desktop first, then make start
