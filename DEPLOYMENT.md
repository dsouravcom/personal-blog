# Deployment Guide

Two deployment options: **Railway** (managed, push-to-deploy) or **Linux VPS** (your own server with PHP, Caddy, Supervisor).

---

## Table of Contents

- [Option A — Railway](#option-a--railway)
- [Option B — Linux VPS with Caddy + Supervisor](#option-b--linux-vps-with-caddy--supervisor)
- [Deploying New Code](#deploying-new-code)
- [Pre-Launch Checklist](#pre-launch-checklist)

---

## Option A — Railway

Railway reads the `Procfile` in the root of this repo and handles everything automatically.

### Step 1 — Push your code to GitHub

```bash
git add .
git commit -m "ready for production"
git push origin main
```

---

### Step 2 — Create the project on Railway

1. Go to [railway.app](https://railway.app) → **New Project**
2. Select **Deploy from GitHub repo** → select this repo
3. Railway auto-detects PHP and uses the `Procfile`

---

### Step 3 — Add a PostgreSQL database

1. In your Railway project → **+ Add Service** → **Database** → **PostgreSQL**
2. Railway automatically injects `DATABASE_URL` — but this app uses individual `DB_*` variables, so go to your **app service → Variables** and manually add:

```
DB_CONNECTION=pgsql
DB_HOST=        # copy "Host" from the Postgres service panel
DB_PORT=5432
DB_DATABASE=    # copy "Database"
DB_USERNAME=    # copy "User"
DB_PASSWORD=    # copy "Password"
```

---

### Step 4 — Set environment variables

In Railway → your app service → **Variables**, add every variable from `.env.production`. The critical ones:

```
APP_KEY=            # generate with: php artisan key:generate --show
APP_ENV=production
APP_DEBUG=false
APP_URL=https://blog.sourav.dev

QUEUE_CONNECTION=database
CACHE_STORE=file

MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_username
MAIL_PASSWORD=your_brevo_password
MAIL_FROM_ADDRESS="updates@newsletter.sourav.dev"
MAIL_FROM_NAME="Sourav Dutta"

R2_ACCOUNT_ID=
R2_ACCESS_KEY_ID=
R2_SECRET_ACCESS_KEY=
R2_BUCKET=personal-blog
R2_ENDPOINT=https://958b05af3ab8d2d84ec134304521071c.r2.cloudflarestorage.com
R2_PUBLIC_URL=https://assets.sourav.dev
R2_SSL_CERT=        # leave empty — Railway uses Linux, system certs work automatically

ADMIN_OTP_EMAIL=hi@sourav.dev
DAILY_EMAIL_LIMIT=280
```

---

### Step 5 — Deploy

Click **Deploy**. Railway runs the `Procfile` which:

- Caches config, routes, views
- Runs `php artisan migrate --force` automatically
- Starts the PHP server

Every `git push origin main` after this triggers a new deploy automatically.

---

### Step 6 — Custom domain

Railway → your service → **Settings** → **Domains** → Add domain → point a `CNAME` to the Railway URL in your DNS provider.

---

## Option B — Linux VPS with Caddy + Supervisor

Steps below assume Ubuntu 22.04 / Debian. Your app lives at `/var/www/personal-blog`.

---

### Step 1 — Install PHP and required extensions

```bash
sudo apt update && sudo apt upgrade -y

sudo apt install -y \
    php8.2-cli \
    php8.2-fpm \
    php8.2-pgsql \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-curl \
    php8.2-zip \
    php8.2-gd \
    php8.2-bcmath \
    php8.2-intl

# Verify
php -v   # should print 8.2.x
```

---

### Step 2 — Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Verify
composer --version
```

---

### Step 3 — Clone the project

```bash
cd /var/www
git clone https://github.com/your-username/personal-blog.git
cd personal-blog

# Install dependencies without dev packages
composer install --no-dev --optimize-autoloader
```

---

### Step 4 — Set up the environment file

```bash
cp .env.production .env
nano .env   # fill in all empty values
```

> See `.env.production` in this repo for all required values with descriptions.

---

### Step 5 — Generate app key and run migrations

```bash
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

---

### Step 6 — Cache everything for production speed

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> Run these 3 commands again every time you deploy new code.

---

### Step 7 — Set correct file permissions

```bash
sudo chown -R www-data:www-data /var/www/personal-blog/storage
sudo chown -R www-data:www-data /var/www/personal-blog/bootstrap/cache
sudo chmod -R 775 /var/www/personal-blog/storage
sudo chmod -R 775 /var/www/personal-blog/bootstrap/cache
```

---

### Step 8 — Configure PHP-FPM

```bash
# Enable and start PHP-FPM
sudo systemctl enable php8.2-fpm
sudo systemctl start php8.2-fpm

# Verify it is running
sudo systemctl status php8.2-fpm
```

The socket this creates: `/run/php/php8.2-fpm.sock` — this is what Caddy will talk to.

---

### Step 9 — Configure Caddy

Edit your Caddyfile (usually at `/etc/caddy/Caddyfile`):

```
blog.sourav.dev {
    root * /var/www/personal-blog/public

    php_fastcgi unix//run/php/php8.2-fpm.sock

    file_server

    encode gzip

    # Limit upload size (matching R2 image uploads)
    request_body {
        max_size 10MB
    }

    # Security headers
    header {
        X-Content-Type-Options "nosniff"
        X-Frame-Options "SAMEORIGIN"
        Referrer-Policy "strict-origin-when-cross-origin"
    }
}
```

Apply the new config:

```bash
sudo caddy fmt --overwrite /etc/caddy/Caddyfile   # auto-format
sudo systemctl reload caddy

# Verify Caddy loaded without errors
sudo systemctl status caddy
```

---

### Step 10 — Set up the queue worker with Supervisor

The app uses `QUEUE_CONNECTION=database`. Without a running queue worker, notification emails will never send. Supervisor keeps the worker alive permanently and restarts it if it crashes.

#### Install Supervisor

```bash
sudo apt install -y supervisor
sudo systemctl enable supervisor
sudo systemctl start supervisor
```

#### Create the worker config

```bash
sudo nano /etc/supervisor/conf.d/personal-blog-worker.conf
```

Paste this content:

```ini
[program:personal-blog-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/personal-blog/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
directory=/var/www/personal-blog
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/personal-blog/storage/logs/worker.log
stopwaitsecs=3600
```

#### Load and start the worker

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start personal-blog-worker:*

# Verify it is running
sudo supervisorctl status
```

Expected output:

```
personal-blog-worker:personal-blog-worker_00   RUNNING   pid 12345, uptime 0:00:05
```

#### Useful Supervisor commands

```bash
sudo supervisorctl status                          # check all workers
sudo supervisorctl restart personal-blog-worker:* # restart after code deploy
sudo supervisorctl stop personal-blog-worker:*    # stop worker
sudo supervisorctl tail personal-blog-worker:personal-blog-worker_00 # live logs
```

---

### Step 11 — Verify everything works

```bash
# Check site loads
curl -I https://blog.sourav.dev
# Expected: HTTP/2 200

# Check queue worker is processing jobs
tail -f /var/www/personal-blog/storage/logs/worker.log

# Check application logs for errors
tail -f /var/www/personal-blog/storage/logs/laravel.log
```

---

## Deploying New Code

### Railway

```bash
git push origin main   # Railway redeploys automatically
```

---

### Linux VPS

```bash
cd /var/www/personal-blog

# 1. Pull latest code
git pull origin main

# 2. Install any new PHP dependencies
composer install --no-dev --optimize-autoloader

# 3. Run any new migrations
php artisan migrate --force

# 4. Clear and rebuild all caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Restart the queue worker so it picks up new code
sudo supervisorctl restart personal-blog-worker:*
```

---

## Pre-Launch Checklist

- [ ] `APP_ENV=production` in `.env`
- [ ] `APP_DEBUG=false` in `.env`
- [ ] `APP_KEY` is set and not empty
- [ ] `APP_URL` matches the actual live domain
- [ ] Database is connected: `php artisan migrate --status` shows no errors
- [ ] Mails send: `php artisan tinker` → `Mail::raw('test', fn($m) => $m->to('hi@sourav.dev')->subject('test'))`
- [ ] R2 credentials are correct and bucket exists
- [ ] R2 bucket has **Public Access** enabled in Cloudflare dashboard
- [ ] `assets.sourav.dev` custom domain is pointed to R2 bucket in Cloudflare
- [ ] `storage/` symlink exists in `public/`: `ls -la public/storage`
- [ ] Queue worker is running: `sudo supervisorctl status`
- [ ] Caddy is serving HTTPS: `curl -I https://blog.sourav.dev`
- [ ] File permissions set correctly on `storage/` and `bootstrap/cache/`

---

## Files in this repo you can ignore for deployment

| File / Folder   | Reason                                          |
| --------------- | ----------------------------------------------- |
| `storage/`      | Auto-generated, never commit or deploy manually |
| `.env`          | Never commit — contains secrets                 |
| `node_modules/` | Not needed on the server, assets are pre-built  |
| `tests/`        | Not needed on the server                        |
