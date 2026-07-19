<x-guest-layout title="Sign up">

    <h1 class="text-xl font-bold text-zinc-900 mb-1">Create your account</h1>
    <p class="text-sm text-zinc-500 mb-6">Start organizing your folders and notes.</p>

    <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Ada Lovelace" autofocus
                class="w-full bg-zinc-100 placeholder-zinc-400 text-zinc-900 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-green-500 transition">
            @error('name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com"
                class="w-full bg-zinc-100 placeholder-zinc-400 text-zinc-900 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-green-500 transition">
            @error('email')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Password</label>
            <input type="password" name="password" placeholder="At least 8 characters"
                class="w-full bg-zinc-100 placeholder-zinc-400 text-zinc-900 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-green-500 transition">
            @error('password')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Confirm password</label>
            <input type="password" name="password_confirmation" placeholder="Repeat password"
                class="w-full bg-zinc-100 placeholder-zinc-400 text-zinc-900 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-green-500 transition">
        </div>

        <button type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-green-600/20">
            Create account
        </button>
    </form>

    <p class="text-sm text-zinc-500 text-center mt-6">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-green-700 hover:text-green-800">Log in</a>
    </p>

</x-guest-layout>
