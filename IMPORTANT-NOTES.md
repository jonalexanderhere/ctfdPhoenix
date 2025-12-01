# ⚠️ Catatan Penting tentang PHP di Vercel

## Status PHP Support di Vercel

**Vercel tidak secara native mendukung PHP runtime untuk serverless functions.**

Vercel secara resmi mendukung:
- ✅ Node.js
- ✅ Python
- ✅ Go
- ✅ Ruby

## Solusi untuk Deploy PHP ke Vercel

### Opsi 1: Menggunakan Vercel dengan Custom Runtime (Tidak Recommended)

PHP tidak didukung secara native. Setup ini mungkin tidak berfungsi langsung tanpa custom runtime.

### Opsi 2: Menggunakan Platform Lain yang Mendukung PHP (Recommended)

Platform yang mendukung PHP:
- **Railway** - https://railway.app (Mendukung PHP)
- **Render** - https://render.com (Mendukung PHP)
- **Heroku** - https://heroku.com (Mendukung PHP)
- **DigitalOcean App Platform** - https://www.digitalocean.com/products/app-platform (Mendukung PHP)
- **AWS Lambda dengan Bref** - https://bref.sh (PHP untuk serverless)

### Opsi 3: Menggunakan Node.js sebagai Proxy (Workaround)

Jika tetap ingin menggunakan Vercel, kita bisa membuat Node.js serverless function yang menjalankan PHP melalui child process atau menggunakan PHP-FPM.

### Opsi 4: Deploy Python Version (Original CTFd)

CTFd aslinya adalah aplikasi Python/Flask. Untuk deploy ke Vercel, lebih baik menggunakan versi Python:

```bash
# Deploy Python version ke Vercel
vercel --prod
```

Vercel akan auto-detect Python dan menggunakan Python runtime.

## Rekomendasi

**Untuk CTFd dengan PHP:**
1. Gunakan **Railway** atau **Render** - lebih mudah setup PHP
2. Atau gunakan **DigitalOcean App Platform**
3. Atau deploy versi Python original ke Vercel

**Untuk Vercel:**
- Gunakan versi Python original CTFd
- Atau convert aplikasi ke Node.js/TypeScript

## Setup untuk Platform Lain

### Railway

1. Install Railway CLI: `npm i -g @railway/cli`
2. Login: `railway login`
3. Init: `railway init`
4. Deploy: `railway up`

### Render

1. Push ke GitHub
2. Connect di Render Dashboard
3. Set build command: `composer install`
4. Set start command: `php -S 0.0.0.0:$PORT index.php`

### DigitalOcean App Platform

1. Push ke GitHub
2. Create App di DigitalOcean
3. Connect repository
4. Set PHP runtime
5. Deploy

## File yang Sudah Dibuat

File-file berikut sudah dibuat untuk setup PHP:
- ✅ `index.php` - Entry point
- ✅ `composer.json` - Dependencies
- ✅ `vercel.json` - Vercel config (mungkin perlu adjustment)
- ✅ `src/` - Application structure
- ✅ `api/` - API endpoints

File-file ini bisa digunakan untuk platform lain yang mendukung PHP.

## Next Steps

1. **Jika ingin tetap menggunakan Vercel**: Pertimbangkan untuk menggunakan versi Python original
2. **Jika ingin menggunakan PHP**: Deploy ke Railway, Render, atau platform lain yang mendukung PHP
3. **Jika ingin hybrid**: Gunakan Vercel untuk frontend static, dan PHP backend di platform lain

## Support

Jika ada pertanyaan:
- Vercel Docs: https://vercel.com/docs
- Railway Docs: https://docs.railway.app
- Render Docs: https://render.com/docs

