<x-guest-layout title="Sign up">

    <h1 class="text-xl font-bold text-white mb-1">Create your account</h1>
    <p class="text-sm text-zinc-400 mb-6">Start organizing your folders and notes.</p>

    <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Ada Lovelace" autofocus
                class="w-full bg-zinc-800 placeholder-zinc-500 text-white text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-indigo-500 transition">
            @error('name')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com"
                class="w-full bg-zinc-800 placeholder-zinc-500 text-white text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-indigo-500 transition">
            @error('email')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Password</label>
            <input type="password" name="password" placeholder="At least 8 characters"
                class="w-full bg-zinc-800 placeholder-zinc-500 text-white text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-indigo-500 transition">
            @error('password')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Confirm password</label>
            <input type="password" name="password_confirmation" placeholder="Repeat password"
                class="w-full bg-zinc-800 placeholder-zinc-500 text-white text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-indigo-500 transition">
        </div>

        <button type="submit"
            class="w-full bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-indigo-500/20">
            Create account
        </button>
    </form>

    <p class="text-sm text-zinc-400 text-center mt-6">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-indigo-400 hover:text-indigo-300">Log in</a>
    </p>

</x-guest-layout>
