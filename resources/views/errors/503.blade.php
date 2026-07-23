<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode - {{ $app_name ?? 'ITAM Enterprise' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: 'DM Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            max-width: 600px;
            padding: 40px;
            background: #1e293b;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.1);
        }
        h1 {
            font-size: 32px;
            margin-bottom: 10px;
            color: #00f0ff;
        }
        p {
            font-size: 16px;
            color: #94a3b8;
            line-height: 1.6;
        }
        .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        a.btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
        }
        a.btn:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🛠️</div>
        <h1>Sedang Dalam Perbaikan</h1>
        <p>Mohon maaf, sistem <strong>{{ $app_name ?? 'ITAM Enterprise' }}</strong> saat ini sedang dalam mode perbaikan (Maintenance Mode).</p>
        <p>Silakan kembali lagi beberapa saat kemudian.</p>
        
        <div style="margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; font-size: 12px; color: #64748b;">
            Hanya Administrator yang dapat masuk ke dalam sistem. <br>
            <a href="{{ route('login') }}?admin=1" style="color: #3b82f6; text-decoration: none;">Login Admin</a>
        </div>
    </div>
</body>
</html>
