# Setup Lengkap CTFd dengan PHP

Panduan lengkap untuk setup dan menjalankan semua proses CTFd dengan PHP.

## 📋 Prerequisites

1. **PHP 8.1+** dengan extensions:
   - `pdo`
   - `pdo_mysql` atau `pdo_pgsql`
   - `json`
   - `mbstring`
   - `curl`
   - `bcmath` (untuk password hashing)

2. **Composer** - https://getcomposer.org

3. **Database** - MySQL 5.7+ atau PostgreSQL 10+

4. **Web Server** - Apache/Nginx atau PHP built-in server

## 🚀 Setup Step-by-Step

### 1. Install Dependencies

```bash
composer install
```

### 2. Setup Environment Variables

Copy `.env.example` ke `.env`:

```bash
cp .env.example .env
```

Edit `.env` dan isi dengan konfigurasi Anda:

```env
# Database
DATABASE_URL=mysql://user:password@localhost:3306/ctfd
# atau
DATABASE_HOST=localhost
DATABASE_PORT=3306
DATABASE_NAME=ctfd
DATABASE_USER=ctfd_user
DATABASE_PASSWORD=your_password

# Security
SECRET_KEY=generate-random-string-here

# CTF Configuration
CTF_NAME=My CTF
CTF_THEME=core
```

**Generate SECRET_KEY:**
```bash
# Linux/Mac
openssl rand -hex 32

# Windows PowerShell
[Convert]::ToBase64String((1..32 | ForEach-Object { Get-Random -Maximum 256 }))
```

### 3. Setup Database

#### Opsi A: Menggunakan Migration Script

```bash
php database/migrate.php
```

#### Opsi B: Manual SQL

```bash
mysql -u your_user -p your_database < database/schema.sql
```

### 4. Test Database Connection

Buat file `test_db.php`:

```php
<?php
require_once 'vendor/autoload.php';

use CTFd\Database;

try {
    $db = Database::getInstance();
    echo "✓ Database connection successful!\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
}
```

Jalankan:
```bash
php test_db.php
```

### 5. Run Application

#### Development (PHP Built-in Server)

```bash
php -S localhost:8000 -t .
```

Buka browser: http://localhost:8000

#### Production (Apache/Nginx)

**Apache (.htaccess):**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

**Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## 🧪 Testing

### Test Homepage
```bash
curl http://localhost:8000/
```

### Test API
```bash
# Get challenges
curl http://localhost:8000/api/v1/challenges

# Get scoreboard
curl http://localhost:8000/api/v1/scoreboard

# Get teams
curl http://localhost:8000/api/v1/teams
```

### Test Authentication
```bash
# Register
curl -X POST http://localhost:8000/register \
  -d "name=Test User" \
  -d "email=test@example.com" \
  -d "password=test123"

# Login
curl -X POST http://localhost:8000/login \
  -d "email=test@example.com" \
  -d "password=test123" \
  -c cookies.txt
```

## 📁 Struktur Project

```
.
├── api/                    # API endpoints
│   ├── index.php          # API entry point
│   └── v1/                # API v1
│       └── index.php      # API v1 handler
├── src/                    # Application source
│   ├── Controllers/        # Controllers
│   ├── Models/            # Database models
│   ├── Utils/             # Utilities
│   ├── Views/             # Views/Templates
│   └── Router.php         # Router
├── database/               # Database files
│   ├── schema.sql         # Database schema
│   └── migrate.php        # Migration script
├── index.php              # Main entry point
├── composer.json          # PHP dependencies
├── vercel.json            # Vercel config
└── .env                   # Environment variables
```

## 🔧 Troubleshooting

### Error: Class not found

**Solusi:**
```bash
composer dump-autoload
```

### Error: Database connection failed

**Check:**
1. Database credentials di `.env`
2. Database server running
3. Database user memiliki permissions
4. Firewall rules

### Error: 404 on all routes

**Check:**
1. `.htaccess` atau nginx config
2. `index.php` ada di root
3. Web server rewrite rules enabled

### Error: Session not working

**Solusi:**
1. Check `session.save_path` di `php.ini`
2. Pastikan directory writable
3. Check `SECRET_KEY` sudah di-set

## 📝 Next Steps

1. **Create Admin User:**
   ```php
   php -r "
   require 'vendor/autoload.php';
   \$user = new CTFd\Models\User([
       'name' => 'Admin',
       'email' => 'admin@example.com',
       'type' => 'admin'
   ]);
   \$user->setPassword('admin123');
   \$user->save();
   echo 'Admin user created!\n';
   "
   ```

2. **Add Challenges:**
   - Login sebagai admin
   - Go to `/admin/challenges`
   - Add challenges

3. **Configure CTF:**
   - Go to `/admin/config`
   - Set CTF name, dates, etc.

## 🚢 Deploy ke Vercel

Lihat `DEPLOY.md` untuk panduan deploy ke Vercel.

## 📚 Dokumentasi

- `README-VERCEL.md` - Dokumentasi Vercel
- `DEPLOY.md` - Panduan deploy
- `QUICKSTART.md` - Quick start guide
- `IMPORTANT-NOTES.md` - Catatan penting tentang PHP di Vercel

## 🆘 Support

Jika ada masalah:
1. Check error logs
2. Check database connection
3. Verify environment variables
4. Check file permissions

## ✅ Checklist Setup

- [ ] PHP 8.1+ installed
- [ ] Composer installed
- [ ] Dependencies installed (`composer install`)
- [ ] `.env` file created and configured
- [ ] Database created
- [ ] Database migration run (`php database/migrate.php`)
- [ ] Database connection tested
- [ ] Web server configured
- [ ] Application running
- [ ] Admin user created
- [ ] Challenges added

## 🎉 Done!

Setelah semua checklist selesai, aplikasi CTFd dengan PHP siap digunakan!

