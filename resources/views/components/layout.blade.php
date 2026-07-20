<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $title ?? 'Hekler' }}</title>
</head>
<body class="bg-zinc-950 text-zinc-200 antialiased">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside class="w-64 bg-zinc-950 border-r border-zinc-900 flex flex-col py-10 px-5 shrink-0">
            <h1 class="text-2xl font-bold tracking-[0.3em] text-white text-center mb-12">HEKLER</h1>

            <nav class="flex flex-col gap-1.5">
                <a href="{{ route('content.index') }}"
                    class="px-4 py-3 rounded-xl text-base font-medium transition
                        {{ request()->routeIs('content.*') ? 'bg-zinc-800 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    Home
                </a>
                <a href="{{ route('commands.index') }}"
                    class="px-4 py-3 rounded-xl text-base font-medium transition
                        {{ request()->routeIs('commands.*') ? 'bg-zinc-800 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    Commands
                </a>
            </nav>

            @auth
                <div class="mt-auto pt-6 border-t border-zinc-900">
                    <p class="px-4 text-sm font-semibold text-zinc-200 truncate">{{ auth()->user()->name }}</p>
                    <p class="px-4 text-xs text-zinc-500 truncate mb-3">{{ auth()->user()->email }}</p>
                    <form action="{{ route('logout') }}" method="POST" class="px-1">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-zinc-500 hover:text-red-400 hover:bg-red-500/10 transition">
                            Log out
                        </button>
                    </form>
                </div>
            @endauth
        </aside>

        {{-- Main --}}
        <main class="flex-1 bg-zinc-800/70 overflow-y-auto p-10">
            <div class="max-w-6xl mx-auto">
                @if (session('status'))
                    <div class="bg-emerald-500/10 text-emerald-400 text-sm font-medium rounded-xl px-5 py-3 mb-8">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>

    </div>

</body>
</html>
