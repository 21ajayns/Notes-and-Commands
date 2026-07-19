<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $title ?? 'Hekler' }}</title>
</head>
<body class="text-zinc-800 antialiased">

    <div class="flex h-screen overflow-hidden bg-gradient-to-b from-green-100 to-white">

        {{-- Sidebar --}}
        <aside class="w-64 bg-white/70 border-r border-green-200/60 flex flex-col py-10 px-5 shrink-0">
            <img src="{{ asset('images/logo.jpg') }}" alt="Hekler" class="w-4/5 h-auto object-contain mx-auto mb-12">

            <nav class="flex flex-col gap-1.5">
                <a href="{{ route('commands.index') }}"
                    class="px-4 py-3 rounded-xl text-base font-medium transition
                        {{ request()->routeIs('commands.*') ? 'bg-green-100 text-green-800' : 'text-zinc-500 hover:text-green-700 hover:bg-green-50' }}">
                    Commands
                </a>
            </nav>
        </aside>

        {{-- Main --}}
        <main class="flex-1 overflow-y-auto p-10">
            <div class="max-w-6xl mx-auto">
                @if (session('status'))
                    <div class="bg-green-100 text-green-700 text-sm font-medium rounded-xl px-5 py-3 mb-8">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>

    </div>

</body>
</html>
