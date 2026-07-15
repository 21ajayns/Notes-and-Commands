<x-layout title="Notes">

    @php
        $categoryStyles = [
            'personal' => 'bg-indigo-500/15 text-indigo-300',
            'work' => 'bg-sky-500/15 text-sky-300',
            'entertainment' => 'bg-pink-500/15 text-pink-300',
        ];
    @endphp

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-white">Notes</h2>
            <p class="text-sm text-zinc-400 mt-1">{{ $notes->count() }} {{ Str::plural('note', $notes->count()) }}</p>
        </div>
        <button type="button" onclick="document.getElementById('create-note').showModal()"
            class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-semibold px-5 py-3 rounded-xl transition shadow-lg shadow-indigo-500/20">
            + Create note
        </button>
    </div>

    {{-- Notes grid --}}
    <div class="grid grid-cols-2 gap-6">
        @foreach($notes as $note)
            <div class="bg-zinc-800/80 border border-zinc-700/50 rounded-2xl p-6 min-h-[140px] flex flex-col gap-3 hover:border-zinc-600 hover:shadow-xl hover:shadow-black/20 transition">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="text-lg font-semibold text-white">{{ $note->title }}</h3>
                    <span class="shrink-0 text-xs font-medium px-2.5 py-1 rounded-full {{ $categoryStyles[$note->category] ?? 'bg-zinc-700 text-zinc-300' }}">
                        {{ ucfirst($note->category) }}
                    </span>
                </div>
                <p class="text-sm text-zinc-400 leading-relaxed line-clamp-3">{{ $note->body }}</p>
            </div>
        @endforeach

        @for($i = $notes->count(); $i < max(8, $notes->count()); $i++)
            <button type="button" onclick="document.getElementById('create-note').showModal()"
                class="bg-zinc-800/40 border border-dashed border-zinc-700 rounded-2xl min-h-[140px] flex items-center justify-center text-zinc-600 hover:text-zinc-400 hover:border-zinc-600 transition">
                <span class="text-3xl font-light">+</span>
            </button>
        @endfor
    </div>

    {{-- Create note modal --}}
    <dialog id="create-note" class="m-auto bg-zinc-900 text-zinc-200 rounded-2xl p-0 w-full max-w-lg backdrop:bg-black/70">
        <form action="{{ route('notes.store') }}" method="POST" class="p-8">
            @csrf
            <h3 class="text-xl font-bold text-white mb-6">New note</h3>

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Title</label>
            <input type="text" name="title" placeholder="Give it a name" value="{{ old('title') }}"
                class="w-full bg-zinc-800 placeholder-zinc-500 text-white text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-indigo-500 transition mb-1">
            @error('title')
                <p class="text-xs text-red-400 mt-1 mb-2">{{ $message }}</p>
            @enderror

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2 mt-5">Body</label>
            <textarea name="body" placeholder="Write something..." rows="10"
                class="w-full bg-zinc-800 placeholder-zinc-500 text-zinc-200 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-indigo-500 transition resize-none mb-1">{{ old('body') }}</textarea>
            @error('body')
                <p class="text-xs text-red-400 mt-1 mb-2">{{ $message }}</p>
            @enderror

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2 mt-5">Category</label>
            <select name="category"
                class="w-auto bg-zinc-800 text-zinc-200 text-xs rounded-lg px-3 py-1.5 outline-none ring-1 ring-transparent focus:ring-indigo-500 transition mb-7">
                @foreach(['personal' => 'Personal', 'work' => 'Work', 'entertainment' => 'Entertainment'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('create-note').close()"
                    class="text-zinc-400 hover:text-white text-sm font-medium px-5 py-3 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-indigo-500/20">
                    Save note
                </button>
            </div>
        </form>
    </dialog>

    @if ($errors->any())
        <script>
            document.getElementById('create-note').showModal();
        </script>
    @endif

</x-layout>
