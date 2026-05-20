<!DOCTYPE html>
<html lang="en" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') — Odo Studio</title>

        <!-- Cinematic Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,700;1,300&family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <style>
            body {
                background-color: #080808;
                color: #8a8a8a;
                font-family: 'Outfit', sans-serif;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                min-h: 100vh;
                height: 100vh;
                overflow: hidden;
            }

            .container {
                text-align: center;
                max-width: 600px;
                padding: 2rem;
                position: relative;
            }

            /* Cinematic Grain Overlay */
            .grain {
                position: fixed;
                inset: 0;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)' opacity='0.08'/%3E%3C/svg%3E");
                opacity: 0.15;
                pointer-events: none;
                z-index: 50;
            }

            .code {
                font-family: 'Cormorant Garamond', serif;
                font-size: 5rem;
                font-weight: 300;
                color: #c9a84c;
                line-height: 1;
                margin-bottom: 1rem;
                letter-spacing: -0.05em;
                font-style: italic;
            }

            .message {
                text-transform: uppercase;
                letter-spacing: 0.4em;
                font-size: 0.65rem;
                font-weight: bold;
                color: #c9a84c;
                opacity: 0.7;
                margin-bottom: 3rem;
            }

            .action {
                margin-top: 2rem;
            }

            .btn {
                display: inline-block;
                padding: 1rem 2.5rem;
                border: 1px solid rgba(201, 168, 76, 0.3);
                color: #c9a84c;
                text-decoration: none;
                text-transform: uppercase;
                font-size: 0.6rem;
                letter-spacing: 0.3em;
                font-weight: bold;
                transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .btn:hover {
                background: #c9a84c;
                color: #080808;
                border-color: #c9a84c;
                letter-spacing: 0.4em;
            }

            .studio-mark {
                font-family: 'Cormorant Garamond', serif;
                font-size: 1.5rem;
                color: white;
                margin-bottom: 4rem;
                opacity: 0.8;
            }

            .studio-mark em {
                color: #c9a84c;
                font-style: italic;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="grain"></div>
        
        <div class="container">
            <div class="studio-mark">Odo <em>Studio</em></div>

            <div class="code">
                @yield('code')
            </div>

            <div class="message">
                @yield('message')
            </div>

            <div class="action">
                <a href="{{ url('/') }}" class="btn">Return to Portal →</a>
            </div>
        </div>
    </body>
</html>
