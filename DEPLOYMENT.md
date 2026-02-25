# Deployment Guide

Two options: **Railway** (push-to-deploy, managed) or **Linux VPS** (your own server with PHP, Caddy, PM2).

---

## Option A — Railway (Recommended for simplicity)

Railway reads the `Procfile` in the root of this repo and handles everything automatically.

### 1. Push your code to GitHub

Make sure your repo is on GitHub and the latest code is pushed.

### 2. Create the project on Railway

1. Go to [railway.app](https://railway.app) → **New Project**
2. Select **Deploy from GitHub repo** → select this repo
3. Railway auto-detects PHP and uses the `Procfile`

### 3. Add a PostgreSQL database

1. In your Railway project, click **+ Add Service** → **Database** → **PostgreSQL**
2. Railway automatically sets `DATABASE_URL` — but this app uses individual `DB_*` variables, so go to your **app service → Variables** and add:

```
DB_CONNECTION=pgsql
DB_HOST=<copy from the Postgres service "Host">
DB_PORT=5432
DB_DATABASE=<copy "Database">
DB_USERNAME=<copy "User">
DB_PASSWORD=<copy "Password">
```

### 4. Set all environment variables

In Railway → your app service → **Variables**, add every line from `.env.example`.
The critical ones:

```
APP_KEY=                 # generate with: php artisan key:generate --show
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-railway-url.up.railway.app

FILESYSTEM_DISK=local    # keep as local for uploads; R2 handled separately

R2_ACCESS_KEY_ID=
R2_SECRET_ACCESS_KEY=
R2_BUCKET=personal-blog
R2_ENDPOINT=https://958b05af3ab8d2d84ec134304521071c.r2.cloudflarestorage.com
R2_PUBLIC_URL=https://assets.sourav.dev
# R2_SSL_CERT → leave empty on Railway (Linux uses system certs automatically)
```

### 5. Deploy

Click **Deploy**. Railway runs the `Procfile` command which:
- Caches config, routes, views
- Runs `php artisan migrate --force` (auto-migrates on every deploy)
- Starts the PHP server

Every `git push` to your repo triggers a new deploy automatically.

### 6. Custom domain (optional)

Railway → your service → **Settings** → **Domains** → Add your domain → point a CNAME to the Railway URL.

---

## Option B — Linux VPS with Caddy + PM2

You already have PHP, Caddy, and PM2 installed. Steps below assume Ubuntu/Debian.

### 1. Install required PHP extensions

```bash
sudo apt update
sudo apt install -y php8.2-cli php8.2-fpm php8.2-pgsql php8.2-mbstring \
     php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl
```

Verify:
```bash
php -v   # should show 8.2
```

### 2. Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version   # verify
```

### 3. Clone and set up the project

```bash
cd /var/www
git clone https://github.com/your-username/personal-blog.git
cd personal-blog

# Install PHP dependencies (no dev packages in production)
composer install --no-dev --optimize-autoloader
```

### 4. Set up the environment file

```bash
cp .env.production.example .env
nano .env   # fill in all values
```

Required values to fill in:

```dotenv
APP_KEY=                 # will generate in next step
APP_URL=https://blog.sourav.dev

DB_HOST=127.0.0.1
DB_DATABASE=personal_blog
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

R2_ACCESS_KEY_ID=
R2_SECRET_ACCESS_KEY=
R2_BUCKET=personal-blog
R2_ENDPOINT=https://958b05af3ab8d2d84ec134304521071c.r2.cloudflarestorage.com
R2_PUBLIC_URL=https://assets.sourav.dev
# R2_SSL_CERT → leave empty on Linux
```

### 5. Generate app key and run migrations

```bash
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

### 6. Cache everything for production speed

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> **Important:** Run these 3 commands again every time you deploy new code.

### 7. Set correct file permissions

```bash
sudo chown -R www-data:www-data /var/www/personal-blog/storage
sudo chown -R www-data:www-data /var/www/personal-blog/bootstrap/cache
sudo chmod -R 775 /var/www/personal-blog/storage
sudo chmod -R 775 /var/www/personal-blog/bootstrap/cache
```

### 8. Configure PHP-FPM

PHP-FPM is what actually runs your app. Check it's running:

```bash
sudo systemctl status php8.2-fpm
sudo systemctl enable php8.2-fpm   # start on boot
sudo systemctl start php8.2-fpm
```

The socket file will be at: `/run/php/php8.2-fpm.sock`

### 9. Configure Caddy

Add this to your Caddyfile (usually at `/etc/caddy/Caddyfile`):

```
blog.sourav.dev {
    root * /var/www/personal-blog/public
    php_fastcgi unix//run/php/php8.2-fpm.sock
    file_server
    encode gzip

    # Upload size limit (match your R2 max)
    request_body {
        max_size 10MB
    }
}
```

Reload Caddy:

```bash
sudo systemctl reload caddy
```

### 10. Set up the queue worker with PM2 (optional but recommended)

The app uses the database as its queue driver. If you want background jobs to process (e.g. cache warming, future email sending), run the queue worker under PM2:

```bash
pm2 start "php /var/www/personal-blog/artisan queue:work --sleep=3 --tries=3" \
    --name=blog-queue \
    --interpreter=none

pm2 save          # persist across reboots
pm2 startup       # auto-start PM2 on server reboot
```

> If you skip this, queue jobs just won't process in the background — the blog still works fine for reading and publishing posts.

### 11. Verify it works

```bash
curl -I https://blog.sourav.dev
# Should return HTTP 200
```

---

## Deploying new code (both options)

### Railway
```bash
git push origin main   # Railway redeploys automatically
```

### Linux VPS
```bash
cd /var/www/personal-blog
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Quick checklist before going live

- [ ] `APP_DEBUG=false` in `.env`
- [ ] `APP_KEY` is set (not empty)
- [ ] `APP_URL` matches your actual domain
- [ ] Database is connected (`php artisan migrate --status` shows no errors)
- [ ] R2 credentials are correct and bucket exists
- [ ] R2 bucket has **Public Access enabled** in Cloudflare dashboard
- [ ] Custom domain's DNS is pointed at your server / Railway URL

---

## Files in this repo you can ignore

- `Dockerfile` and `docker/` — only needed if you use Docker directly; not required for Railway or plain VPS
- `storage/` — auto-generated, never deploy manually
