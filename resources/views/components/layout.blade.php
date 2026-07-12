<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $title ?? 'Hekler' }}</title>
</head>
<body class="bg-black text-zinc-200 antialiased">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside class="w-56 bg-black border-r border-zinc-900 flex flex-col py-8 px-4 shrink-0">
            <h1 class="text-xl font-semibold tracking-[0.35em] text-zinc-300 text-center mb-10">HEKLER</h1>

            <nav class="flex flex-col gap-1">
                <a href="{{ route('notes.index') }}"
                    class="px-4 py-2.5 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('notes.*') ? 'bg-zinc-800 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    Notes
                </a>
                <a href="{{ route('commands.index') }}"
                    class="px-4 py-2.5 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('commands.*') ? 'bg-zinc-800 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    Commands
                </a>
            </nav>
        </aside>

        {{-- Main --}}
        <main class="flex-1 bg-zinc-800/70 overflow-y-auto p-8">
            <div class="max-w-5xl mx-auto">
                @if (session('status'))
                    <div class="bg-emerald-500/10 text-emerald-400 text-sm rounded-lg px-4 py-2.5 mb-6">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>

    </div>

</body>
</html>
