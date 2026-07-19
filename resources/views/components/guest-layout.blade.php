<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $title ?? 'Hekler' }}</title>
</head>
<body class="antialiased">

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-green-100 to-white p-6">
        <div class="w-full max-w-sm">
            <img src="{{ asset('images/logo.jpg') }}" alt="Hekler" class="w-3/5 h-auto object-contain mx-auto mb-8">

            <div class="bg-white/80 border border-green-200 rounded-2xl shadow-xl shadow-green-900/5 p-8">
                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
