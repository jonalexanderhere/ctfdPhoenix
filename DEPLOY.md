# Panduan Deploy CTFd ke Vercel

## Langkah-langkah Deploy

### 1. Install Dependencies

```bash
composer install
```

### 2. Setup Environment Variables

Buat file `.env` atau set di Vercel Dashboard:

```bash
# Database (Required)
DATABASE_URL=mysql://user:password@host:3306/database

# Security (Required)
SECRET_KEY=your-random-secret-key-here

# CTF Configuration
CTF_NAME=My CTF
CTF_THEME=core
```

### 3. Deploy ke Vercel

#### Opsi A: Menggunakan Vercel CLI

```bash
# Install Vercel CLI
npm i -g vercel

# Login
vercel login

# Deploy
vercel

# Deploy ke production
vercel --prod
```

#### Opsi B: Menggunakan GitHub Integration

1. Push code ke GitHub
2. Buka https://vercel.com
3. Klik "New Project"
4. Import repository
5. Vercel akan auto-detect PHP project
6. Set environment variables
7. Deploy!

### 4. Set Environment Variables di Vercel

1. Buka project di Vercel Dashboard
2. Settings → Environment Variables
3. Tambahkan semua variables dari `.env`

**Variables yang diperlukan:**
- `DATABASE_URL` - Connection string database
- `SECRET_KEY` - Secret key untuk session (generate random string)

**Variables opsional:**
- `CTF_NAME` - Nama CTF
- `CTF_THEME` - Theme yang digunakan
- `REDIS_URL` - Redis connection (jika menggunakan cache)

### 5. Setup Database

#### Menggunakan Vercel Postgres (Recommended)

1. Di Vercel Dashboard, buat Postgres database
2. Copy connection string
3. Set sebagai `DATABASE_URL`

#### Menggunakan External Database

1. Setup MySQL/PostgreSQL database
2. Pastikan accessible dari internet (atau whitelist Vercel IPs)
3. Format connection string:
   - MySQL: `mysql://user:password@host:port/database`
   - PostgreSQL: `postgresql://user:password@host:port/database`

### 6. Verify Deployment

Setelah deploy, buka URL yang diberikan Vercel:
- Home: `https://your-project.vercel.app/`
- API: `https://your-project.vercel.app/api/v1/challenges`

## Troubleshooting

### Error: Composer dependencies not found

**Solusi:**
```bash
# Pastikan composer.json ada
# Install dependencies
composer install

# Commit vendor/ jika perlu (tidak recommended)
# Atau pastikan Vercel build command: composer install
```

### Error: Database connection failed

**Solusi:**
1. Check format `DATABASE_URL`
2. Pastikan database accessible
3. Check firewall rules
4. Test connection string secara manual

### Error: 404 on all routes

**Solusi:**
1. Check `vercel.json` configuration
2. Pastikan `index.php` ada di root
3. Check Vercel function logs
4. Pastikan routes sudah benar

### Error: PHP extension missing

**Solusi:**
Vercel PHP runtime sudah include extension umum. Jika masih error:
1. Check `composer.json` untuk dependencies
2. Pastikan extension yang diperlukan tersedia
3. Check Vercel runtime documentation

## Custom Domain

1. Di Vercel Dashboard → Settings → Domains
2. Add custom domain
3. Follow DNS instructions
4. Wait for DNS propagation

## Monitoring

- **Logs**: Vercel Dashboard → Logs
- **Analytics**: Vercel Dashboard → Analytics
- **Functions**: Vercel Dashboard → Functions

## Next Steps

Setelah deploy berhasil:
1. Setup admin user (jika belum)
2. Import challenges
3. Configure CTF settings
4. Test semua functionality

## Support

- Vercel Docs: https://vercel.com/docs
- Vercel Community: https://github.com/vercel/vercel/discussions

