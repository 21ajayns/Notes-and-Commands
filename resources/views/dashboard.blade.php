<x-layout title="Notes">

    <div id="landing-overlay" class="fixed inset-0 z-50" style="transition: transform 600ms ease-in-out;">
        <img src="{{ asset('images/opening.jpg') }}" alt="Hekler" class="h-full w-full object-cover">
    </div>

    <script>
        (function () {
            var overlay = document.getElementById('landing-overlay');

            if (sessionStorage.getItem('splashSeen')) {
                overlay.remove();
                return;
            }

            overlay.classList.add('cursor-pointer');
            let leaving = false;

            function dismiss() {
                if (leaving) return;
                leaving = true;
                sessionStorage.setItem('splashSeen', '1');
                overlay.style.transform = 'translateY(-100%)';
                setTimeout(() => overlay.remove(), 600);
            }

            document.addEventListener('click', dismiss);
            document.addEventListener('keydown', dismiss);
        })();
    </script>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-zinc-900">Notes</h2>
            <p class="text-sm text-zinc-500 mt-1">{{ $notes->count() }} {{ Str::plural('note', $notes->count()) }}</p>
        </div>
        <button type="button" onclick="document.getElementById('create-note').showModal()"
            class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-5 py-3 rounded-xl transition shadow-lg shadow-green-600/20">
            + Create note
        </button>
    </div>

    {{-- Notes grid --}}
    <div class="grid grid-cols-2 gap-6">
        @foreach($notes as $note)
            <div class="bg-white/80 border border-green-200 rounded-2xl p-6 min-h-[140px] flex flex-col gap-3 hover:border-green-300 hover:shadow-xl hover:shadow-green-900/5 transition">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="text-lg font-semibold text-zinc-900">{{ $note->title }}</h3>
                    @if ($note->folder)
                        <span class="shrink-0 text-xs font-medium px-2.5 py-1 rounded-full bg-green-500/10 text-green-700">
                            {{ $note->folder->name }}
                        </span>
                    @endif
                </div>
                <p class="text-sm text-zinc-500 leading-relaxed line-clamp-3">{{ $note->body }}</p>
            </div>
        @endforeach

        @for($i = $notes->count(); $i < max(8, $notes->count()); $i++)
            <button type="button" onclick="document.getElementById('create-note').showModal()"
                class="bg-white/40 border border-dashed border-green-200 rounded-2xl min-h-[140px] flex items-center justify-center text-zinc-400 hover:text-green-600 hover:border-green-400 transition">
                <span class="text-3xl font-light">+</span>
            </button>
        @endfor
    </div>

    {{-- Create note modal --}}
    <dialog id="create-note" class="m-auto bg-white text-zinc-800 rounded-2xl p-0 w-full max-w-lg backdrop:bg-black/40">
        <form action="{{ route('notes.store') }}" method="POST" class="p-8">
            @csrf
            <h3 class="text-xl font-bold text-zinc-900 mb-6">New note</h3>

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Title</label>
            <input type="text" name="title" placeholder="Give it a name" value="{{ old('title') }}"
                class="w-full bg-zinc-100 placeholder-zinc-400 text-zinc-900 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-green-500 transition mb-1">
            @error('title')
                <p class="text-xs text-red-600 mt-1 mb-2">{{ $message }}</p>
            @enderror

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2 mt-5">Body</label>
            <textarea name="body" placeholder="Write something..." rows="10"
                class="w-full bg-zinc-100 placeholder-zinc-400 text-zinc-900 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-green-500 transition resize-none mb-1">{{ old('body') }}</textarea>
            @error('body')
                <p class="text-xs text-red-600 mt-1 mb-2">{{ $message }}</p>
            @enderror

            <div class="flex items-center justify-between mb-2 mt-5">
                <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Folder</label>
                <button type="button" onclick="document.getElementById('create-folder').showModal()"
                    class="text-xs font-semibold text-green-700 hover:text-green-800 transition">
                    + New folder
                </button>
            </div>
            <select name="folder_id"
                class="w-auto bg-zinc-100 text-zinc-800 text-xs rounded-lg px-3 py-1.5 outline-none ring-1 ring-transparent focus:ring-green-500 transition mb-7">
                <option value="" @selected(old('folder_id') === null)>No folder</option>
                @foreach($folders as $folder)
                    <option value="{{ $folder->id }}" @selected((int) old('folder_id') === $folder->id)>{{ $folder->name }}</option>
                @endforeach
            </select>

            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('create-note').close()"
                    class="text-zinc-500 hover:text-zinc-900 text-sm font-medium px-5 py-3 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-green-600/20">
                    Save note
                </button>
            </div>
        </form>
    </dialog>

    {{-- Create folder modal --}}
    <dialog id="create-folder" class="m-auto bg-white text-zinc-800 rounded-2xl p-0 w-full max-w-sm backdrop:bg-black/40">
        <form action="{{ route('folders.store') }}" method="POST" class="p-8">
            @csrf
            <h3 class="text-xl font-bold text-zinc-900 mb-6">New folder</h3>

            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2">Name</label>
            <input type="text" name="name" placeholder="Folder name"
                class="w-full bg-zinc-100 placeholder-zinc-400 text-zinc-900 text-base rounded-xl px-4 py-3 outline-none ring-1 ring-transparent focus:ring-green-500 transition mb-1">
            @error('name')
                <p class="text-xs text-red-600 mt-1 mb-2">{{ $message }}</p>
            @enderror

            <div class="flex items-center justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('create-folder').close()"
                    class="text-zinc-500 hover:text-zinc-900 text-sm font-medium px-5 py-3 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-green-600/20">
                    Save folder
                </button>
            </div>
        </form>
    </dialog>

    @if ($errors->any())
        <script>
            document.getElementById('{{ $errors->has('name') ? 'create-folder' : 'create-note' }}').showModal();
        </script>
    @endif

</x-layout>
