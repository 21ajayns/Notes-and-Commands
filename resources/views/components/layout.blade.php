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
                <a href="{{ route('content.index') }}"
                    class="px-4 py-3 rounded-xl text-base font-medium transition
                        {{ request()->routeIs('content.*') ? 'bg-green-100 text-green-800' : 'text-zinc-500 hover:text-green-700 hover:bg-green-50' }}">
                    Home
                </a>
                <a href="{{ route('commands.index') }}"
                    class="px-4 py-3 rounded-xl text-base font-medium transition
                        {{ request()->routeIs('commands.*') ? 'bg-green-100 text-green-800' : 'text-zinc-500 hover:text-green-700 hover:bg-green-50' }}">
                    Commands
                </a>
            </nav>

            @auth
                <div class="mt-auto pt-6 border-t border-green-200/60">
                    <p class="px-4 text-sm font-semibold text-zinc-700 truncate">{{ auth()->user()->name }}</p>
                    <p class="px-4 text-xs text-zinc-400 truncate mb-3">{{ auth()->user()->email }}</p>
                    <form action="{{ route('logout') }}" method="POST" class="px-1">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-zinc-500 hover:text-red-600 hover:bg-red-50 transition">
                            Log out
                        </button>
                    </form>
                </div>
            @endauth
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
