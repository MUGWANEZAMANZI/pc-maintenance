# Deploying Laravel PC Maintenance App to Render

## 1. Prerequisites
- Render account: https://render.com
- A PostgreSQL (recommended) or MySQL managed database on Render (SQLite is ephemeral; avoid for production)
- Repository pushed to GitHub (public or private with Render access)

## 2. Files Added For Container Deployment
- `Dockerfile` (multi-stage: composer, node build, final runtime with nginx + php-fpm)
- `entrypoint.sh` (runs optimize, migrations, seeds optionally, then starts services)
- `nginx.conf` (vhost config honoring `$PORT`)
- `.dockerignore` (reduces build context)

## 3. Environment Variables (Render -> Service Settings)
| Key | Suggested Value | Notes |
|-----|------------------|-------|
| `APP_NAME` | pcmaintenance | Optional |
| `APP_ENV` | production | |
| `APP_DEBUG` | false | |
| `APP_URL` | https://your-service.onrender.com | |
| `APP_KEY` | (leave blank, auto-generated first run or set manually) | If set, remove key:generate step |
| `LOG_CHANNEL` | stack | |
| `DB_CONNECTION` | pgsql | Use `pgsql` for Postgres |
| `DB_HOST` | `<render-db-host>` | From Render DB dashboard |
| `DB_PORT` | 5432 | Postgres default |
| `DB_DATABASE` | `<render-db-name>` | |
| `DB_USERNAME` | `<render-db-user>` | |
| `DB_PASSWORD` | `<render-db-password>` | Mark as protected |
| `CACHE_DRIVER` | file | Or redis if provisioned |
| `SESSION_DRIVER` | file | |
| `QUEUE_CONNECTION` | database | Or redis/sqs etc. |
| `RUN_MIGRATIONS` | true | Runs migrations at container start |
| `RUN_SEED` | false | Set true only if you want initial seed each deploy |
| `PORT` | 10000 (Render sets automatically) | Provided automatically—no need to define manually |

If you do keep SQLite (not recommended):
- Add a Render persistent disk and mount to `/var/www/html/database`
- Point `DB_DATABASE=/var/www/html/database/database.sqlite`

## 4. Service Creation Steps
1. New Web Service -> Select repository
2. Environment: Docker
3. Build Command: (leave empty, Dockerfile handles build)
4. Start Command: (leave empty; Dockerfile ENTRYPOINT used)
5. Add environment variables listed above
6. (Optional) Add Postgres DB via Render and attach

## 5. First Deploy Flow
1. Render builds image using multi-stage Dockerfile
2. Container starts `entrypoint.sh`
3. Storage and cache permissions set
4. Key generated if missing (`APP_KEY`)
5. Config/routes/views cached
6. Migrations executed (if `RUN_MIGRATIONS=true`)
7. Seeds executed (if `RUN_SEED=true`)
8. `php-fpm` + `nginx` launched

## 6. Asset Building (Vite)
- Node build stage runs `npm run build` producing `public/build`
- Ensure `vite.config.js` outputs to `public/build`
- If you add new front-end deps, they will be installed during subsequent builds

## 7. Common Issues
| Problem | Cause | Fix |
|---------|-------|-----|
| 502/Timeout | DB unreachable | Verify DB vars and Render network | 
| APP_KEY missing error | Key not generated | Set `APP_KEY` or let entrypoint generate | 
| Assets 404 | Vite build failed | Check build logs; ensure scripts exist | 
| Redis errors | Missing driver | Switch `CACHE_DRIVER`/`QUEUE_CONNECTION` to `file`/`database` | 
| Migration lock | Long-running migration | Clear with `php artisan migrate:status` locally, redeploy |

## 8. Manual Tasks After Deploy
```bash
# SSH into service (Render Shell) then:
php artisan tinker
# Create additional admin if needed
App\Models\User::create([
  'name' => 'Render Admin',
  'email' => 'render-admin@pcm.local',
  'password' => bcrypt('ChangeMe123!'),
  'role' => 'admin'
]);
```

## 9. Scaling
- App is stateless; scale horizontally by adding instances
- Use Redis for session & cache if scaling beyond 1 instance

## 10. Logging & Monitoring
- Logs visible in Render dashboard
- For structured logging use `LOG_STACK_CHANNEL` and external aggregators if needed

## 11. Updating Dependencies
- Changes to `composer.json` or `package.json` trigger layer rebuild
- Keep lock files committed for reproducible builds

## 12. Zero-Downtime Migrations
- Keep migrations fast and additive
- Avoid destructive changes without maintenance window

## 13. Optional Render YAML (Infra as Code)
Create `render.yaml` at repo root:
```yaml
services:
  - type: web
    name: pcmaintenance
    env: docker
    plan: starter
    autoDeploy: true
    healthCheckPath: /
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: RUN_MIGRATIONS
        value: true
      - key: RUN_SEED
        value: false
      # Add DB vars here or link a database resource
```

## 14. Quick Local Docker Test
```bash
docker build -t pcm:dev .
docker run --rm -p 8080:8080 -e RUN_MIGRATIONS=true pcm:dev
# Visit http://localhost:8080
```

## 15. Security Notes
- Set strong admin passwords; avoid default 'password'
- Keep `APP_DEBUG=false` in production
- Rotate DB credentials periodically
- Consider a WAF/monitoring solution for high traffic

---
Your project is now container-ready for Render deployment.
