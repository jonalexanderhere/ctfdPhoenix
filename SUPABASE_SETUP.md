# Setup Database dengan Supabase

Panduan lengkap untuk setup CTFd dengan Supabase (PostgreSQL).

## 🚀 Setup Supabase

### 1. Buat Project di Supabase

1. Buka https://supabase.com
2. Sign up / Login
3. Klik "New Project"
4. Isi:
   - Project Name: `ctfd`
   - Database Password: (buat password yang kuat)
   - Region: Pilih yang terdekat
5. Tunggu project dibuat (sekitar 2 menit)

### 2. Dapatkan Connection String

1. Di Supabase Dashboard, klik "Settings" → "Database"
2. Scroll ke "Connection string"
3. Pilih "URI" tab
4. Copy connection string, format:
   ```
   postgresql://postgres:[YOUR-PASSWORD]@[PROJECT-REF].supabase.co:5432/postgres
   ```

### 3. Setup Environment Variables

Edit file `.env`:

```env
# Supabase Database
DATABASE_URL=postgresql://postgres:your_password@xxxxx.supabase.co:5432/postgres

# Atau gunakan individual components
DATABASE_HOST=xxxxx.supabase.co
DATABASE_PORT=5432
DATABASE_NAME=postgres
DATABASE_USER=postgres
DATABASE_PASSWORD=your_password
DATABASE_DRIVER=pgsql
DATABASE_SSL=true
```

### 4. Install PostgreSQL Extension untuk PHP

```bash
# Ubuntu/Debian
sudo apt-get install php-pgsql

# CentOS/RHEL
sudo yum install php-pgsql

# macOS (Homebrew)
brew install php-pgsql

# Windows
# Enable extension di php.ini:
# extension=pdo_pgsql
# extension=pgsql
```

### 5. Run Database Migration

```bash
php database/migrate.php
```

Atau jalankan SQL langsung di Supabase SQL Editor:

1. Buka Supabase Dashboard → SQL Editor
2. Copy isi file `database/schema.sql`
3. Paste dan run

### 6. Test Connection

```bash
php test_db.php
```

## 📝 Supabase SQL Editor

Untuk menjalankan query manual:

1. Buka Supabase Dashboard
2. Klik "SQL Editor"
3. Buat query baru
4. Paste SQL dan run

## 🔒 Security

### Row Level Security (RLS)

Supabase menggunakan RLS untuk security. Untuk CTFd, kita perlu disable RLS atau setup policies:

```sql
-- Disable RLS untuk development (tidak recommended untuk production)
ALTER TABLE users DISABLE ROW LEVEL SECURITY;
ALTER TABLE challenges DISABLE ROW LEVEL SECURITY;
ALTER TABLE solves DISABLE ROW LEVEL SECURITY;
-- ... untuk semua tabel
```

Atau setup policies yang sesuai dengan kebutuhan.

## 🔧 Troubleshooting

### Error: Connection refused

**Solusi:**
1. Check connection string format
2. Verify Supabase project is active
3. Check firewall/network settings
4. Verify password is correct

### Error: SSL connection required

**Solusi:**
Tambahkan di `.env`:
```env
DATABASE_SSL=true
```

### Error: Extension pdo_pgsql not found

**Solusi:**
1. Install PostgreSQL extension untuk PHP
2. Enable di `php.ini`
3. Restart web server

### Error: Table not found

**Solusi:**
1. Run migration: `php database/migrate.php`
2. Atau run SQL di Supabase SQL Editor

## 📊 Supabase Dashboard

Fitur berguna di Supabase:

- **Table Editor**: View/edit data secara visual
- **SQL Editor**: Run SQL queries
- **Database**: View schema, indexes, etc.
- **Logs**: View database logs
- **API**: Auto-generated REST API

## 🔗 Connection Pooling

Supabase menyediakan connection pooling. Untuk production, gunakan:

```
postgresql://postgres:password@xxxxx.supabase.co:6543/postgres
```

Port `6543` untuk connection pooling (lebih efisien untuk serverless).

## ✅ Checklist

- [ ] Supabase project created
- [ ] Connection string obtained
- [ ] `.env` configured
- [ ] PostgreSQL PHP extension installed
- [ ] Database migration run
- [ ] Connection tested
- [ ] RLS configured (if needed)

## 🎉 Done!

Database Supabase siap digunakan!

