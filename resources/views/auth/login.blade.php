<x-guest-layout title="Log in">

    <h1 class="text-xl font-bold text-white mb-1">Welcome back</h1>
    <p class="text-sm text-zinc-400 mb-6">Log in to your folders and notes.</p>

    <form action="{{ route('login.attempt') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autofocus
                class="w-full bg-zinc-800 placeholder-zinc-500 text-white text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-indigo-500 transition">
            @error('email')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Password</label>
            <input type="password" name="password" placeholder="••••••••"
                class="w-full bg-zinc-800 placeholder-zinc-500 text-white text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-indigo-500 transition">
            @error('password')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-zinc-400">
            <input type="checkbox" name="remember" class="rounded border-zinc-600 bg-zinc-800 text-indigo-500 focus:ring-indigo-500">
            Remember me
        </label>

        <button type="submit"
            class="w-full bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-indigo-500/20">
            Log in
        </button>
    </form>

    <p class="text-sm text-zinc-400 text-center mt-6">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-semibold text-indigo-400 hover:text-indigo-300">Sign up</a>
    </p>

</x-guest-layout>
