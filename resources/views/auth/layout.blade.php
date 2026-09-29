<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · Cove</title>
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16.png?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon-180.png?v=2">
    <link rel="manifest" href="/manifest.json?v=2">
    <meta name="theme-color" content="#120e0a">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=Orbitron:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-ink-950 text-ink-100 antialiased font-sans text-sm">
    <main class="h-full overflow-y-auto">
        <div class="min-h-full flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-sm flex flex-col gap-8">
            <div class="flex flex-col items-center gap-3">
                <svg viewBox="0 0 64 64" class="w-12 h-12" role="img" aria-label="Cove">
                    <defs>
                        <linearGradient id="coveGradAuth" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#E8A455"/>
                            <stop offset="100%" stop-color="#A23E4C"/>
                        </linearGradient>
                    </defs>
                    <path d="M43 13 A22 22 0 1 0 43 51" fill="none" stroke="url(#coveGradAuth)" stroke-width="4" stroke-linecap="round"/>
                    <circle cx="43" cy="13" r="3" fill="#E8A455"/>
                    <circle cx="43" cy="51" r="3" fill="#A23E4C"/>
                </svg>
                <span
                    class="text-2xl font-bold tracking-[0.3em]"
                    style="font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg, #E8A455, #A23E4C); -webkit-background-clip: text; background-clip: text; color: transparent;"
                >COVE</span>
            </div>

            <section class="bg-ink-900 border border-white/[0.07] shadow-[inset_0_2px_0_0_rgba(162,62,76,0.8)] p-7 flex flex-col gap-6">
                <div class="flex flex-col gap-1">
                    <h1 class="text-lg font-semibold text-white">@yield('heading')</h1>
                    <p class="text-sm text-ink-500">@yield('subheading')</p>
                </div>

                @yield('content')
            </section>

            <p class="text-center text-sm text-ink-500">@yield('footer')</p>
        </div>
        </div>
    </main>
</body>
</html>
