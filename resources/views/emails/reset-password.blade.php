<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - GO Business</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f5f5f5; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); padding: 40px 32px; text-align: center; }
        .header-logo { display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 14px; margin-bottom: 16px; }
        .header-logo svg { width: 28px; height: 28px; color: white; fill: none; stroke: white; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: -0.5px; }
        .header p { color: rgba(255,255,255,0.75); font-size: 13px; margin-top: 4px; }
        .body { padding: 36px 32px; }
        .greeting { font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 12px; }
        .body p { font-size: 14px; color: #6b7280; line-height: 1.7; margin-bottom: 16px; }
        .btn-wrapper { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; padding: 14px 36px; background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); color: #ffffff !important; text-decoration: none; border-radius: 10px; font-size: 15px; font-weight: 700; letter-spacing: 0.2px; box-shadow: 0 4px 14px rgba(124,58,237,0.4); }
        .divider { border: none; border-top: 1px solid #f3f4f6; margin: 24px 0; }
        .link-fallback { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px 16px; }
        .link-fallback p { font-size: 12px; color: #9ca3af; margin-bottom: 6px; }
        .link-fallback a { font-size: 11px; color: #7c3aed; word-break: break-all; }
        .expiry-box { display: flex; align-items: center; gap: 10px; background: #fff7ed; border: 1px solid #fed7aa; border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; }
        .expiry-box svg { width: 18px; height: 18px; fill: none; stroke: #ea580c; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; flex-shrink: 0; }
        .expiry-box p { font-size: 12px; color: #9a3412; margin: 0; }
        .footer { background: #f9fafb; padding: 20px 32px; text-align: center; border-top: 1px solid #f3f4f6; }
        .footer p { font-size: 11px; color: #9ca3af; line-height: 1.6; }
        .footer a { color: #7c3aed; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">
                <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            </div>
            <h1>GO Business</h1>
            <p>Sistem POS Modern untuk UMKM Indonesia</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Halo, {{ $user->name }}! 👋</p>
            <p>
                Kami menerima permintaan untuk mereset password akun GO Business Anda yang terdaftar dengan email <strong>{{ $user->email }}</strong>.
            </p>
            <p>
                Klik tombol di bawah ini untuk membuat password baru. Jika Anda tidak merasa membuat permintaan ini, abaikan email ini dan password Anda tidak akan berubah.
            </p>

            <!-- Expiry warning -->
            <div class="expiry-box">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p>Link ini hanya berlaku selama <strong>60 menit</strong> sejak email dikirim.</p>
            </div>

            <!-- CTA Button -->
            <div class="btn-wrapper">
                <a href="{{ $url }}" class="btn">🔐 Reset Password Saya</a>
            </div>

            <hr class="divider">

            <!-- Fallback link -->
            <div class="link-fallback">
                <p>Jika tombol tidak berfungsi, salin dan buka link berikut di browser:</p>
                <a href="{{ $url }}">{{ $url }}</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                Email ini dikirim secara otomatis oleh sistem GO Business.<br>
                Jangan balas email ini. &copy; {{ date('Y') }} GO Business. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</body>
</html>
