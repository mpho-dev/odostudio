<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? config('app.name') }}</title>
    <style>
        body { margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #000000; color: #ffffff; line-height: 1.6; }
        .wrapper { width: 100%; max-width: 600px; margin: 0 auto; background-color: #0a0a0a; border: 1px solid #1a1a1a; }
        .header { background-color: #000000; padding: 50px 30px; text-align: center; border-bottom: 1px solid #d4af37; }
        .header h1 { font-family: Georgia, serif; color: #ffffff; margin: 0; font-size: 32px; font-weight: normal; letter-spacing: 2px; }
        .header h1 em { color: #d4af37; font-style: italic; }
        .tagline { color: #888888; font-size: 10px; text-transform: uppercase; letter-spacing: 4px; margin-top: 15px; }
        .content { padding: 50px 40px; }
        .greeting { font-size: 20px; margin-bottom: 25px; color: #ffffff; font-family: Georgia, serif; font-style: italic; }
        .message-body { margin-bottom: 40px; color: #cccccc; font-size: 16px; }
        .details-box { background-color: #111111; border: 1px solid #222222; padding: 30px; border-radius: 4px; margin-bottom: 40px; }
        .details-title { font-size: 12px; text-transform: uppercase; letter-spacing: 3px; color: #d4af37; font-weight: bold; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #222222; padding-bottom: 15px; }
        .detail-row { margin-bottom: 12px; border-bottom: 1px solid #1a1a1a; padding-bottom: 10px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: bold; color: #888888; display: inline-block; width: 140px; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }
        .detail-value { color: #ffffff; font-size: 15px; }
        .btn-container { text-align: center; margin: 40px 0; }
        .btn { display: inline-block; background-color: #d4af37; color: #000000; text-decoration: none; padding: 15px 40px; font-size: 13px; text-transform: uppercase; letter-spacing: 3px; font-weight: bold; transition: background-color 0.3s; border-radius: 2px; }
        .footer { background-color: #000000; padding: 40px 30px; text-align: center; border-top: 1px solid #1a1a1a; }
        .footer p { color: #555555; font-size: 11px; margin: 0; letter-spacing: 1px; }
        .studio-name { color: #d4af37; font-family: Georgia, serif; font-size: 18px; margin-bottom: 15px; font-style: italic; }
        .social-links { margin-top: 20px; }
        .social-links a { color: #888888; text-decoration: none; font-size: 10px; margin: 0 10px; text-transform: uppercase; letter-spacing: 2px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Odo <em>Studio</em></h1>
            <div class="tagline">Cinematic Excellence</div>
        </div>
        
        <div class="content">
            @yield('content')
        </div>
        
        <div class="footer">
            <div class="studio-name">Odo Studio</div>
            <p>&copy; {{ date('Y') }} Odo Studio. All rights reserved.</p>
            <p style="margin-top: 10px;">Automated Production Dispatch &bull; South Africa</p>
            <div class="social-links">
                <a href="#">Instagram</a>
                <a href="#">Vimeo</a>
                <a href="#">Website</a>
            </div>
        </div>
    </div>
</body>
</html>
