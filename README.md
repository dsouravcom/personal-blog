# Sourav Dutta — Personal Blog

A self-hosted personal blog built with **Laravel 11**, **PostgreSQL**, and **Cloudflare R2** for image storage. Features a full admin panel, subscriber email notifications with a configurable daily send-limit, post analytics, comment moderation, and a tag system.

---

## Tech Stack

| Layer           | Technology                                |
| --------------- | ----------------------------------------- |
| Framework       | Laravel 11 (PHP 8.3)                      |
| Database        | PostgreSQL                                |
| Image Storage   | Cloudflare R2 (S3-compatible)             |
| Frontend Build  | Vite + Tailwind CSS                       |
| Mail            | SMTP (configurable) via Laravel Mailables |
| Queue           | Database queue driver                     |
| Process Manager | Procfile (Railway) / hosting panel (VPS)  |
| Web Server      | Caddy (VPS)                               |

---

## Features

### Public Blog

- Post listing with pagination
- Individual post pages with cover image, tags, SEO meta, and Open Graph tags
- Tag archive pages
- XML sitemap (`/sitemap.xml`)
- Post view tracking (device, browser, country, UTM params)
- Post likes (per-IP, duplicate-protected)
- Comment system with spam protection (IP throttling)
- Email subscription with signed unsubscribe links

### Admin Panel (`/admin`)

- OTP-based two-factor login (email OTP)
- Full post CRUD with rich content editor
- AJAX image uploads directly to Cloudflare R2 (cover image + OG image)
- SEO fields: meta title, description, keywords, canonical URL
- Open Graph fields: OG title, description, OG image
- Tag management (auto-created on post save)
- Comment moderation (approve / disapprove / delete)
- Analytics dashboard per-post and site-wide

### Email Notifications

- Subscribers are notified when a new post is published
- Terminal-styled HTML email with post title, excerpt, cover image, and tags
- **Daily send-limit** enforced via a cache counter — configurable with `DAILY_EMAIL_LIMIT` in `.env`
- When the daily limit is hit, remaining subscribers are queued and notified the next day at midnight (rolling offset across days until all subscribers are reached)

---

## Local Setup

### Prerequisites

- PHP 8.3+ with extensions: `pdo_pgsql`, `pgsql`, `mbstring`, `xml`, `curl`, `zip`, `gd`, `bcmath`, `intl`
- Composer
- Node.js 18+ and npm
- PostgreSQL

### 1. Clone and install dependencies

```bash
git clone https://github.com/your-username/personal-blog.git
cd personal-blog
composer install
npm install
```

### 2. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and fill in:

```dotenv
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=personal_blog
DB_USERNAME=your_user
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_smtp_user
MAIL_PASSWORD=your_smtp_password
MAIL_FROM_ADDRESS="you@example.com"
MAIL_FROM_NAME="Sourav Dutta"

# Max emails sent per calendar day for post notifications
DAILY_EMAIL_LIMIT=300

# Cloudflare R2 (optional for local dev — images won't upload without it)
R2_ACCOUNT_ID=
R2_ACCESS_KEY_ID=
R2_SECRET_ACCESS_KEY=
R2_BUCKET=
R2_ENDPOINT=https://<ACCOUNT_ID>.r2.cloudflarestorage.com
R2_PUBLIC_URL=https://pub-REPLACE_ME.r2.dev

ADMIN_OTP_EMAIL=your_admin@example.com
```

### 3. Run migrations and seed

```bash
php artisan migrate

# Seed only the admin user (recommended for production)
php artisan db:seed --class=UserSeeder

# Seed sample posts as well (local dev only)
php artisan db:seed --class=PostSeeder

# Or run both at once
php artisan db:seed
```

### 4. Build frontend assets

```bash
npm run dev      # development with HMR
npm run build    # production build
```

### 5. Start the application

```bash
php artisan serve
```

### 6. Queue worker & scheduler (required for email notifications)

On your hosting panel, configure:

**Queue worker command:**

```
php artisan queue:work --sleep=3 --tries=3
```

**Cron job (Laravel scheduler):**

```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

**Verify queue is processing:**

```bash
# Check pending jobs in the DB
php artisan queue:monitor

# Or inspect directly
php artisan tinker --execute="echo DB::table('jobs')->count() . ' pending jobs';"
```

**Verify scheduler is running:**

```bash
php artisan schedule:list
```

> Without the queue worker, post-publish email notifications will sit in the `jobs` table and never send.

---

## Database Migrations

Migrations run in this order — all columns are defined consistently so there are no ordering conflicts:

| Migration                                  | Description                                                               |
| ------------------------------------------ | ------------------------------------------------------------------------- |
| `create_users_table`                       | Admin user                                                                |
| `create_posts_table`                       | Posts with cover image, OG image, R2 keys, publication state              |
| `create_subscribers_table`                 | Email subscribers                                                         |
| `add_seo_fields_to_posts_table`            | Meta, OG title/description, cover alt/caption, tags JSON, structured data |
| `create_comments_table`                    | Post comments with approval flag                                          |
| `create_tags_table`                        | Tag taxonomy                                                              |
| `create_post_likes_table`                  | Per-IP like tracking                                                      |
| `create_post_views_table`                  | Detailed view analytics                                                   |
| `create_post_tag_table`                    | Post ↔ Tag pivot                                                          |
| `drop_tags_column_from_posts_table`        | Removes deprecated JSON tags column                                       |
| `add_unsubscribed_at_to_subscribers_table` | Soft-unsubscribe support                                                  |

---

## Email Notification System

When a post is **first published** (either on create or when toggled from draft to published), the `SendPostPublishedNotifications` job is dispatched. It:

1. Reads `DAILY_EMAIL_LIMIT` from `.env` (default: `300`)
2. Checks a cache key `post_notifications_daily_count:YYYY-MM-DD` to see how many have been sent today
3. Sends up to the remaining quota to the next batch of active subscribers
4. If subscribers remain after today's quota is exhausted, re-queues itself for `tomorrow()->startOfDay()` with the current offset

The daily counter expires automatically at midnight so the quota resets each day.

---

## Seeding

| Command                                  | Effect                      |
| ---------------------------------------- | --------------------------- |
| `php artisan db:seed --class=UserSeeder` | Creates the admin user only |
| `php artisan db:seed --class=PostSeeder` | Seeds 13 sample posts       |
| `php artisan db:seed`                    | Runs both seeders           |

Default admin credentials (change immediately in production):

- **Email:** `admin@blog.com`
- **Password:** `password`

---

## Deployment

See [DEPLOYMENT.md](DEPLOYMENT.md) for full step-by-step instructions for both:

- **Railway** (push-to-deploy, managed, recommended for simplicity)
- **Linux VPS** with Caddy (your own server)

### Quick deploy checklist

- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` is set
- [ ] `APP_URL` matches your domain
- [ ] `php artisan migrate --force` runs cleanly
- [ ] R2 bucket exists and has Public Access enabled
- [ ] Queue worker is configured in hosting panel (or Railway worker process)
- [ ] Cron job is set up: `* * * * * php artisan schedule:run`
- [ ] Storage permissions: `chown -R www-data:www-data storage bootstrap/cache`

---

## Project Structure (key files)

```
app/
  Http/Controllers/
    Admin/PostController.php     ← CRUD + R2 image upload + dispatch notifications
    Admin/AuthController.php     ← OTP-based admin login
  Jobs/
    SendPostPublishedNotifications.php  ← Daily-limited subscriber notifications
  Mail/
    NewPostPublished.php         ← Post notification mailable
    SubscriptionConfirmed.php    ← Welcome email mailable
  Services/
    R2ImageService.php           ← Cloudflare R2 upload/delete
    ViewTrackerService.php       ← Post view analytics

resources/views/emails/
  new-post-published.blade.php   ← Post notification email template
  subscription-confirmed.blade.php ← Welcome email template

config/
  blog.php                       ← daily_email_limit config

database/
  migrations/                    ← 11 ordered migration files
  seeders/
    UserSeeder.php               ← Admin user
    PostSeeder.php               ← Sample posts
    DatabaseSeeder.php           ← Calls both seeders
```

---

## License

Personal project — not open for contributions. Source is shared for reference only.
