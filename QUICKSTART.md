# Quick Start - Deploy CTFd ke Vercel

## 🚀 Deploy dalam 5 Menit

### 1. Install Dependencies

```bash
composer install
```

### 2. Setup Environment Variables

Buat file `.env`:

```env
DATABASE_URL=mysql://user:password@host:3306/database
SECRET_KEY=generate-random-string-here
CTF_NAME=My CTF
```

**Generate SECRET_KEY:**
```bash
# Linux/Mac
openssl rand -hex 32

# Windows PowerShell
[Convert]::ToBase64String((1..32 | ForEach-Object { Get-Random -Maximum 256 }))
```

### 3. Deploy ke Vercel

#### Opsi A: Vercel CLI

```bash
npm i -g vercel
vercel login
vercel --prod
```

#### Opsi B: GitHub Integration

1. Push ke GitHub
2. Import di Vercel Dashboard
3. Set environment variables
4. Deploy!

### 4. Set Environment Variables di Vercel

Di Vercel Dashboard → Settings → Environment Variables:

- `DATABASE_URL` - Database connection string
- `SECRET_KEY` - Random secret key
- `CTF_NAME` - Nama CTF (optional)

### 5. Done! 🎉

Buka URL yang diberikan Vercel dan aplikasi sudah running!

## 📝 Catatan Penting

- **Database**: Gunakan Vercel Postgres atau external database
- **Storage**: File uploads disimpan di `/tmp` (ephemeral)
- **Sessions**: Menggunakan cookies dengan `SECRET_KEY`

## 🔧 Troubleshooting

**Error: Composer not found**
```bash
# Pastikan composer.json ada
composer install
```

**Error: Database connection failed**
- Check format `DATABASE_URL`
- Pastikan database accessible dari internet

**Error: 404 on routes**
- Check `vercel.json` configuration
- Pastikan `index.php` ada di root

## 📚 Dokumentasi Lengkap

Lihat `README-VERCEL.md` dan `DEPLOY.md` untuk dokumentasi lengkap.

