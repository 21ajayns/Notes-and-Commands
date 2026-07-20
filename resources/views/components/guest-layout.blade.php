<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $title ?? 'Hekler' }}</title>
</head>
<body class="antialiased">

    <div class="min-h-screen flex items-center justify-center bg-black p-6">
        <div class="w-full max-w-sm">
            <h1 class="text-2xl font-bold tracking-[0.3em] text-white text-center mb-8">HEKLER</h1>

            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-xl shadow-black/40 p-8">
                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
