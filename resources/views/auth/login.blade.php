<x-guest-layout title="Log in">

    <h1 class="text-xl font-bold text-zinc-900 mb-1">Welcome back</h1>
    <p class="text-sm text-zinc-500 mb-6">Log in to your folders and notes.</p>

    <form action="{{ route('login.attempt') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autofocus
                class="w-full bg-zinc-100 placeholder-zinc-400 text-zinc-900 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-green-500 transition">
            @error('email')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Password</label>
            <input type="password" name="password" placeholder="••••••••"
                class="w-full bg-zinc-100 placeholder-zinc-400 text-zinc-900 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-green-500 transition">
            @error('password')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-zinc-500">
            <input type="checkbox" name="remember" class="rounded border-zinc-300 text-green-600 focus:ring-green-500">
            Remember me
        </label>

        <button type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-green-600/20">
            Log in
        </button>
    </form>

    <p class="text-sm text-zinc-500 text-center mt-6">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-semibold text-green-700 hover:text-green-800">Sign up</a>
    </p>

</x-guest-layout>
