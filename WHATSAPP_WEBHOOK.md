# WhatsApp Webhook Setup

Panduan setup webhook WhatsApp untuk notifikasi First Blood.

## 📱 Fitur

- ✅ Notifikasi First Blood otomatis
- ✅ Format: "Congrats [Nama] - [Sekolah]! 🔥 FIRST BLOOD! 🔥 on [Challenge] [Category]"
- ✅ Support untuk Practice challenges
- ✅ Configurable via environment variables

## 🔧 Setup

### 1. Dapatkan Webhook URL

Anda bisa menggunakan berbagai service untuk WhatsApp webhook:

#### Opsi A: WhatsApp Business API (Official)
- Daftar di https://business.whatsapp.com
- Setup webhook di dashboard
- Dapatkan webhook URL

#### Opsi B: Third-party Service
- **Twilio**: https://www.twilio.com/whatsapp
- **360dialog**: https://www.360dialog.com
- **ChatAPI**: https://www.chatapi.com
- **Wati.io**: https://www.wati.io

#### Opsi C: Custom Webhook (WhatsApp Gateway)
Jika Anda punya WhatsApp gateway sendiri, gunakan endpoint tersebut.

### 2. Setup Environment Variables

Edit file `.env`:

```env
# WhatsApp Webhook
WHATSAPP_ENABLED=true
WHATSAPP_WEBHOOK_URL=https://api.whatsapp.com/webhook/your-endpoint
```

### 3. Format Webhook

Webhook akan mengirim POST request dengan format:

```json
{
  "message": "Congrats Vincent Aurigo Osnard - SMKN 4 Bandar Lampung!\n\n🔥 FIRST BLOOD! 🔥\non s0 rand0m [Practice] [Crypto]"
}
```

### 4. Custom Webhook Handler

Jika webhook service Anda memerlukan format berbeda, edit file:
`src/Services/WhatsAppWebhook.php`

Contoh untuk Twilio:

```php
$response = $this->client->post($this->webhookUrl, [
    'json' => [
        'To' => 'whatsapp:+1234567890', // Group WhatsApp number
        'From' => 'whatsapp:+0987654321', // Your Twilio number
        'Body' => $message
    ]
]);
```

## 📝 Format Notifikasi

### First Blood
```
Congrats Vincent Aurigo Osnard - SMKN 4 Bandar Lampung!

🔥 FIRST BLOOD! 🔥
on s0 rand0m [Practice] [Crypto]
```

### Regular Solve (optional)
```
✅ Vincent Aurigo Osnard solved s0 rand0m [Crypto]
```

## 🧪 Testing

### Test Webhook Manual

Buat file `test_webhook.php`:

```php
<?php
require_once 'vendor/autoload.php';

use CTFd\Services\WhatsAppWebhook;

$webhook = new WhatsAppWebhook();

// Test First Blood
$result = $webhook->sendFirstBlood(
    'Vincent Aurigo Osnard',
    'SMKN 4 Bandar Lampung',
    's0 rand0m',
    'Crypto',
    true // isPractice
);

if ($result) {
    echo "✓ Webhook sent successfully!\n";
} else {
    echo "✗ Webhook failed. Check logs.\n";
}
```

Jalankan:
```bash
php test_webhook.php
```

### Test dari Browser/Postman

```bash
curl -X POST http://localhost:8000/api/test/webhook \
  -H "Content-Type: application/json" \
  -d '{
    "user_name": "Test User",
    "affiliation": "Test School",
    "challenge_name": "Test Challenge",
    "category": "Crypto"
  }'
```

## 🔍 Debugging

### Enable Error Logging

Check PHP error logs:
```bash
tail -f /var/log/php_errors.log
```

Atau enable di `.env`:
```env
APP_DEBUG=true
```

### Check Webhook Response

Edit `src/Services/WhatsAppWebhook.php` untuk log response:

```php
$response = $this->client->post($this->webhookUrl, [...]);
$body = $response->getBody()->getContents();
error_log("Webhook response: " . $body);
```

## ⚙️ Configuration

### Disable Webhook

```env
WHATSAPP_ENABLED=false
```

### Change Webhook URL

```env
WHATSAPP_WEBHOOK_URL=https://new-webhook-url.com/endpoint
```

## 🔐 Security

### Protect Webhook URL

1. Gunakan HTTPS
2. Add authentication token
3. Verify request signature

Contoh dengan token:

```php
$response = $this->client->post($this->webhookUrl, [
    'json' => ['message' => $message],
    'headers' => [
        'Authorization' => 'Bearer ' . $_ENV['WHATSAPP_TOKEN']
    ]
]);
```

## 📱 WhatsApp Group

Untuk mengirim ke WhatsApp Group:

1. Buat group WhatsApp
2. Invite bot/number yang terhubung dengan webhook
3. Dapatkan group ID
4. Update webhook URL dengan group ID

## ✅ Checklist

- [ ] WhatsApp webhook service setup
- [ ] Webhook URL obtained
- [ ] `.env` configured
- [ ] Webhook tested
- [ ] First Blood notification working
- [ ] Error handling configured

## 🎉 Done!

WhatsApp webhook siap digunakan! Setiap First Blood akan otomatis terkirim ke WhatsApp.

