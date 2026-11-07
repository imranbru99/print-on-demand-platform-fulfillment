# Deployment

## Processes

| Process | Command | Notes |
|---------|---------|-------|
| HTTP | `php-fpm` + Nginx (see Docker) or `php artisan serve` (dev only) |
| Queue worker | `php artisan queue:work --sleep=3 --tries=3` | Required when `QUEUE_CONNECTION` is not `sync` |
| Scheduler | `php artisan schedule:work` **or** cron `* * * * * php /path/to/artisan schedule:run` | No custom schedules in repo yet |
| Frontend | `npm run build` | Vite manifest in `public/build` |

Queue configuration: `config/queue.php` (default `database`; Redis recommended at scale).

## Docker Compose (reference)

```bash
docker compose up -d --build
docker compose exec app composer install --no-dev --optimize-autoloader
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
# Edit .env: DB_HOST=mysql, DB_DATABASE, QUEUE_CONNECTION=redis, REDIS_HOST=redis
docker compose exec app php artisan migrate --force
docker compose exec app npm ci && npm run build
```

Services:

- **nginx** — port `${APP_PORT:-8080}`
- **app** — PHP 8.3 FPM
- **mysql** — persistent volume `pod-mysql`
- **redis** — queue/cache
- **queue** — runs `queue:work`
- **scheduler** — runs `schedule:work`

PostgreSQL: uncomment `postgres` service in `docker-compose.yml` and set `DB_CONNECTION=pgsql`.

## Native production checklist

1. PHP 8.2+ with required extensions (match Dockerfile)
2. MySQL 8 or PostgreSQL
3. Redis for queue/cache (recommended)
4. `composer install --no-dev --optimize-autoloader`
5. `php artisan migrate --force`
6. `php artisan config:cache`, `route:cache`, `view:cache` when `APP_DEBUG=false`
7. `npm ci && npm run build`
8. Supervisor (or systemd) for `queue:work` and scheduler
9. Nginx/Apache pointing `public/` as web root
10. `storage/` and `bootstrap/cache/` writable by PHP user

## Environment

- `APP_ENV=production`, `APP_DEBUG=false`
- Strong `APP_KEY`, secure `DB_PASSWORD`
- Configure mail (`MAIL_*`) for real notifications
- Set `AWS_*` if using S3 for uploads
- Integration secrets (`STRIPE_*`, `PRINTFUL_*`, etc.) only when adapters are live

## Filament

Run `php artisan filament:upgrade` after Composer updates (post-autoload script already invokes this in dev).

## CI

GitHub Actions workflow `.github/workflows/ci.yml`: Composer install, migrate against MySQL service, `npm run build`, `php artisan test`.

## Zero-downtime notes

- Run migrations before switching traffic
- Restart queue workers after deploy: `php artisan queue:restart`
- No Octane/FrankenPHP config included
