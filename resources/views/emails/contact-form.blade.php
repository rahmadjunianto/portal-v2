<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru dari Portal Kemenag Nganjuk</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .content {
            padding: 30px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        .info-item {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #059669;
        }
        .info-item.full-width {
            grid-column: 1 / -1;
        }
        .info-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 15px;
            color: #1f2937;
            font-weight: 500;
        }
        .message-box {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .message-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .message-content {
            font-size: 15px;
            color: #1f2937;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .timestamp {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📬 Pesan Baru</h1>
            <p>Portal Kementerian Agama Kabupaten Nganjuk</p>
        </div>

        <div class="content">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nama Pengirim</div>
                    <div class="info-value">{{ $name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        <a href="mailto:{{ $email }}" style="color: #059669;">{{ $email }}</a>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">No. WhatsApp</div>
                    <div class="info-value">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}" style="color: #059669;">{{ $phone }}</a>
                    </div>
                </div>
                <div class="info-item full-width">
                    <div class="info-label">Subjek</div>
                    <div class="info-value">{{ $subject }}</div>
                </div>
            </div>

            <div class="message-box">
                <div class="message-label">Isi Pesan</div>
                <div class="message-content">{{ $messageContent }}</div>
            </div>

            <div class="timestamp">
                Dikirim pada: {{ $submittedAt }}
            </div>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis dari Portal Kemenag Nganjuk.</p>
            <p style="margin-top: 10px;">
                <strong>Kementerian Agama Kabupaten Nganjuk</strong><br>
                Jalan Dermojoyo 22, Payaman, Kec. Nganjuk, Kabupaten Nganjuk, Jawa Timur
            </p>
        </div>
    </div>
</body>
</html>
