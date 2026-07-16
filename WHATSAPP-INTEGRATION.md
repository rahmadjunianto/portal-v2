# WhatsApp Integration Guide

Panduan integrasi Go WhatsApp Web Multi-Device dengan Laravel Chatbot AI

## Prerequisites
- Go WhatsApp Web Multi-Device library: https://github.com/aldinokemal/go-whatsapp-web-multidevice
- Laravel project dengan chatbot AI yang sudah dikonfigurasi

## Endpoint API

### Base URL
```
http://your-domain.com/api/whatsapp
```

### 1. Webhook Endpoint (POST)
Endpoint utama untuk menerima pesan WhatsApp.

**URL:** `POST /api/whatsapp/webhook`

**Headers:**
```
Content-Type: application/json
X-Webhook-Secret: your-secure-webhook-secret-key
```

**Request Body:**
```json
{
    "phone": "6281234567890",
    "name": "John Doe",
    "message": "Halo, informasi layanan nikah",
    "message_id": "msg_123456",
    "timestamp": "2024-01-15T10:30:00Z",
    "is_group": false,
    "group_name": null
}
```

**Response Success (200):**
```json
{
    "status": "success",
    "reply": "Assalamualaikum! Terima kasih...",
    "message_id": "msg_123456"
}
```

**Response Rate Limited (429):**
```json
{
    "status": "rate_limited",
    "reply": "Terlalu banyak permintaan. Mohon tunggu sebentar."
}
```

### 2. Health Check (GET)
Cek status service.

**URL:** `GET /api/whatsapp/health`

**Response:**
```json
{
    "status": "healthy",
    "ai_configured": true,
    "webhook_configured": true,
    "timestamp": "2024-01-15T10:30:00Z"
}
```

### 3. Get Conversation History (GET)
Ambil riwayat percakapan user.

**URL:** `GET /api/whatsapp/conversation/{phone}`

**Response:**
```json
{
    "phone": "6281234567890",
    "messages": [
        {"role": "user", "content": "Halo", "timestamp": "..."},
        {"role": "assistant", "content": "Assalamualaikum!", "timestamp": "..."}
    ],
    "count": 2
}
```

### 4. Clear Conversation (DELETE)
Hapus riwayat percakapan user.

**URL:** `DELETE /api/whatsapp/conversation/{phone}`

**Response:**
```json
{
    "status": "success",
    "message": "Conversation history cleared"
}
```

### 5. Broadcast Message (POST)
Kirim pesan broadcast ke beberapa nomor.

**URL:** `POST /api/whatsapp/broadcast`

**Request Body:**
```json
{
    "numbers": ["6281234567890", "6289876543210"],
    "message": "Pengumuman penting dari Kemenag Nganjuk"
}
```

**Response:**
```json
{
    "status": "completed",
    "total": 2,
    "results": [
        {"phone": "6281234567890", "success": true, "status": 200},
        {"phone": "6289876543210", "success": true, "status": 200}
    ]
}
```

## Konfigurasi Environment

Tambahkan ke file `.env`:

```env
# WhatsApp Integration Configuration
WHATSAPP_WEBHOOK_SECRET=your-secure-webhook-secret-key
WHATSAPP_ENABLED=true
WHATSAPP_ALLOW_GROUP=false
WHATSAPP_RATE_LIMIT_MAX=5
WHATSAPP_RATE_LIMIT_WINDOW=60

# WhatsApp Auto Reply Settings
WHATSAPP_AUTO_REPLY_ENABLED=true
WHATSAPP_OFFLINE_MESSAGE="Terima kasih telah menghubungi kami..."
WHATSAPP_GREETING_ENABLED=true
WHATSAPP_GREETING_MESSAGE="Assalamualaikum! Terima kasih..."

# WhatsApp Session Settings
WHATSAPP_HISTORY_DURATION=24
WHATSAPP_MAX_HISTORY=20
```

## Contoh Kode Go WA

### Handler untuk Webhook

```go
package main

import (
    "bytes"
    "encoding/json"
    "net/http"
    "fmt"
)

const (
    LaravelWebhookURL = "http://your-domain.com/api/whatsapp/webhook"
    WebhookSecret = "your-secure-webhook-secret-key"
)

type WhatsAppMessage struct {
    Phone      string `json:"phone"`
    Name       string `json:"name"`
    Message    string `json:"message"`
    MessageID  string `json:"message_id,omitempty"`
    IsGroup    bool   `json:"is_group,omitempty"`
    GroupName  string `json:"group_name,omitempty"`
}

type WebhookResponse struct {
    Status   string `json:"status"`
    Reply    string `json:"reply"`
    MessageID string `json:"message_id,omitempty"`
}

func sendToWebhook(msg WhatsAppMessage) (string, error) {
    jsonData, err := json.Marshal(msg)
    if err != nil {
        return "", err
    }

    req, err := http.NewRequest("POST", LaravelWebhookURL, bytes.NewBuffer(jsonData))
    if err != nil {
        return "", err
    }

    req.Header.Set("Content-Type", "application/json")
    req.Header.Set("X-Webhook-Secret", WebhookSecret)

    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        return "", err
    }
    defer resp.Body.Close()

    var webhookResp WebhookResponse
    if err := json.NewDecoder(resp.Body).Decode(&webhookResp); err != nil {
        return "", err
    }

    return webhookResp.Reply, nil
}

// Example usage in message handler
func onMessageReceived(phone, name, message string) {
    msg := WhatsAppMessage{
        Phone:   phone,
        Name:    name,
        Message: message,
    }

    reply, err := sendToWebhook(msg)
    if err != nil {
        fmt.Printf("Error: %v\n", err)
        return
    }

    // Send reply back to WhatsApp user
    fmt.Printf("AI Reply: %s\n", reply)
}
```

### Full Webhook Handler (Gin Framework)

```go
package main

import (
    "net/http"
    "github.com/gin-gonic/gin"
    "github.com/aldinokemal/go-whatsapp-web-multidevice/handlers"
)

func main() {
    r := gin.Default()
    
    // Your existing WhatsApp handler
    waHandler := handlers.NewWAHandler()
    
    // Add webhook endpoint
    r.POST("/api/chatbot/webhook", func(c *gin.Context) {
        var msg struct {
            Phone    string `json:"phone"`
            Name     string `json:"name"`
            Message  string `json:"message"`
            IsGroup  bool   `json:"is_group"`
        }
        
        if err := c.ShouldBindJSON(&msg); err != nil {
            c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
            return
        }
        
        // Process with AI and get reply
        reply := processWithAI(msg.Phone, msg.Name, msg.Message)
        
        // Send reply via WhatsApp
        waHandler.SendMessage(msg.Phone, reply)
        
        c.JSON(http.StatusOK, gin.H{
            "status": "success",
            "reply": reply,
        })
    })
    
    r.Run(":8080")
}

func processWithAI(phone, name, message string) string {
    // Call Laravel webhook
    // Implementation depends on your Go WA setup
    return "AI Response"
}
```

## Testing

### Test Webhook dengan curl

```bash
curl -X POST http://localhost/api/whatsapp/webhook \
  -H "Content-Type: application/json" \
  -H "X-Webhook-Secret: your-secure-webhook-secret-key" \
  -d '{
    "phone": "6281234567890",
    "name": "Test User",
    "message": "Informasi layanan nikah"
  }'
```

### Test Health Check

```bash
curl http://localhost/api/whatsapp/health
```

## Security Notes

1. **Webhook Secret**: Selalu gunakan webhook secret yang kuat dan rahasiakan
2. **HTTPS**: Pastikan menggunakan HTTPS untuk production
3. **Rate Limiting**: Rate limit sudah dikonfigurasi (5 request per menit per nomor)
4. **Input Validation**: Semua input sudah disanitasi dan divalidasi
5. **Logging**: Semua request akan logged untuk monitoring

## Troubleshooting

### Webhook tidak terpanggil
- Pastikan Go WA service berjalan dan terkoneksi ke internet
- Cek apakah URL webhook sudah benar
- Verifikasi webhook secret match dengan konfigurasi .env

### Response lambat
- Cek koneksi internet
- Pastikan AI service (OpenAI/compatible) respond dengan baik
- Cek log Laravel untuk error details

### Rate limit terus-terusan
- Tunggu 60 detik (reset window)
- Atau increase `WHATSAPP_RATE_LIMIT_MAX` di .env

## Support

Untuk bantuan lebih lanjut, cek dokumentasi:
- Laravel Chatbot: `./docs/chatbot.md`
- Go WhatsApp Library: https://github.com/aldinokemal/go-whatsapp-web-multidevice
