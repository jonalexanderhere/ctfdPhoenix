# CTFd Deployment ke Vercel dengan PHP

Panduan lengkap untuk deploy CTFd ke Vercel menggunakan PHP.

## Prerequisites

1. Akun Vercel (gratis di [vercel.com](https://vercel.com))
2. Vercel CLI (opsional, untuk deploy dari local)
3. Database (MySQL/PostgreSQL) - bisa menggunakan Vercel Postgres atau external database

## Setup

### 1. Install Dependencies

```bash
composer install
```

### 2. Konfigurasi Environment Variables

Buat file `.env` berdasarkan `.env.example`:

```bash
cp .env.example .env
```

Edit `.env` dan isi dengan konfigurasi yang sesuai:

- `DATABASE_URL`: URL database (format: `mysql://user:password@host:port/database`)
- `SECRET_KEY`: Secret key untuk session (generate random string)
- `CTF_NAME`: Nama CTF
- `CTF_THEME`: Theme yang digunakan (default: `core`)

### 3. Deploy ke Vercel

#### Menggunakan Vercel CLI:

```bash
# Install Vercel CLI (jika belum)
npm i -g vercel

# Login ke Vercel
vercel login

# Deploy
vercel

# Deploy ke production
vercel --prod
```

#### Menggunakan GitHub/GitLab:

1. Push code ke repository GitHub/GitLab
2. Import project di Vercel Dashboard
3. Vercel akan otomatis detect dan deploy

### 4. Set Environment Variables di Vercel

Di Vercel Dashboard:
1. Go to Project Settings
2. Environment Variables
3. Add semua variables dari `.env`

**Important Variables:**
- `DATABASE_URL`
- `SECRET_KEY`
- `CTF_NAME`
- `CTF_THEME`

## Struktur Project

```
.
├── api/                 # Serverless functions
│   ├── index.php       # API entry point
│   └── v1/             # API v1 endpoints
├── src/                # Application source
│   ├── Controllers/    # Controllers
│   └── Views/          # Views/Templates
├── index.php           # Main entry point
├── vercel.json         # Vercel configuration
├── composer.json       # PHP dependencies
└── .env.example        # Environment variables template
```

## API Endpoints

### Public API

- `GET /api/v1/challenges` - List challenges
- `GET /api/v1/scoreboard` - Scoreboard
- `GET /api/v1/teams` - List teams
- `GET /api/v1/users` - List users

### Web Routes

- `/` - Home page
- `/login` - Login page
- `/register` - Registration page
- `/challenges` - Challenges list
- `/scoreboard` - Scoreboard
- `/admin` - Admin dashboard

## Database Setup

### Menggunakan Vercel Postgres:

1. Di Vercel Dashboard, buat Postgres database
2. Copy connection string
3. Set sebagai `DATABASE_URL` di environment variables

### Menggunakan External Database:

1. Setup MySQL/PostgreSQL database
2. Format connection string:
   - MySQL: `mysql://user:password@host:port/database`
   - PostgreSQL: `postgresql://user:password@host:port/database`
3. Set di `DATABASE_URL`

## Development

### Local Development:

```bash
# Install dependencies
composer install

# Run dengan PHP built-in server
php -S localhost:8000 -t .
```

### Testing:

```bash
# Test API endpoints
curl http://localhost:8000/api/v1/challenges
```

## Troubleshooting

### Error: PHP extension not found

Pastikan extension yang diperlukan sudah diinstall:
- `ext-json`
- `ext-mbstring`
- `ext-curl`
- `ext-pdo`
- `ext-pdo_mysql` atau `ext-pdo_pgsql`

### Error: Database connection failed

1. Check `DATABASE_URL` format
2. Pastikan database accessible dari Vercel
3. Check firewall rules jika menggunakan external database

### Error: 404 on all routes

1. Check `vercel.json` configuration
2. Pastikan routes sudah benar
3. Check Vercel function logs

## Notes

- Vercel PHP runtime menggunakan PHP 8.2
- File uploads akan disimpan di `/tmp` (ephemeral storage)
- Untuk persistent storage, gunakan S3 atau external storage
- Session storage menggunakan cookies (pastikan `SECRET_KEY` sudah di-set)

## Support

Untuk issues atau questions:
1. Check Vercel documentation: https://vercel.com/docs
2. Check PHP runtime docs: https://github.com/vercel-community/php

